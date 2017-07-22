System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Locker = System.Locker,
        _ = System.Pandora;

    var lockTime,
        showTips = (obj, str, _str) => {
            pincode = '';
            obj.val('');
            obj.attr('placeholder', _str);
            setTimeout(() => {
                obj.attr('placeholder', str);
            }, 5000);
        },
        ctimes = 0,
        ctimer = null,
        connecting = (stop) => {
            if (stop) {
                clearInterval(ctimer);
                ctimes = 0;
                ctimer = null;
                Locker.pincode.removeClass('connecting').attr('placeholder', ' P I N ');
            } else {
                Locker.pincode.addClass('connecting');
                Locker.pincode.attr('placeholder', 'CONNECT');
                var m, s;
                ctimer = setInterval(function() {
                    ctimes++;
                    m = ctimes % 6;
                    s = m < 3 ? m : 6 - m;
                    Locker.pincode.attr('placeholder', 'CONNECTING'.substr(s, 7));
                }, 500);
            }
        };

    var pincode = '',
        checkpincodeOnKeyUp = (e) => {
            if (e.which === 8 && pincode.length > 0) {
                pincode = pincode.slice(0, pincode.length - 1);
                Locker.pincode.val(pincode.replace(/\d/, '*'));
            }
        },
        checkpincodeOnChange = (e) => {
            Locker.pincode.val(pincode);
        },
        checkpincodeOnKeyPress = (e) => {
            var keyName = e.keyName;
            if (/^\d$/.test(keyName)) {
                pincode += keyName;
                Locker.tipsarea.html(keyName).show();
                setTimeout(() => {
                    if (pincode.length === 6) {
                        Locker.pincode.val('');
                        checkContact(pincode);
                        pincode = '';
                    } else {
                        Locker.pincode.val(pincode.replace(/\d/g, '*'));
                    }
                }, 1);
            } else {
                Locker.tipsarea.html('Err').show();
                setTimeout(() => {
                    Locker.pincode.val(pincode);
                }, 1);
            }
            setTimeout(() => {
                Locker.tipsarea.hide();
            }, 1500);
        };

    var checkContact = (pincode) => {
            YangRAM.set({
                url: YangRAM.SubmitDIR + 'uoi/visa/checkpin/',
                data: {
                    args: pincode
                },
                done: oncontact,
                fail: oncontactfailed
            });
            connecting();
        },
        oncontact = (data) => {
            connecting(true);
            var preg = /^\[\{"username":/;
            if (data.match(preg)) {
                var admin = JSON.parse(data)[0];
                if (admin.username === System.User.toLowerCase()) {
                    showTips(Locker.pincode, ' P I N ', 'CHECKED');
                    return onunLock();
                }
            }
            if (data === '[{"error":"PIN_ERROR"}]') {
                showTips(Locker.pincode, ' P I N ', 'PIN_ERR');
            }
            return;
        },
        onunLock = () => {
            Locker.timer && clearTimeout(Locker.timer);
            Locker.pincode.val(' 3 2 1 ');
            setTimeout(() => {
                Locker.pincode.val(' * 2 1 ');
            }, 1000);
            setTimeout(() => {
                Locker.pincode.val(' * * 1 ');
            }, 2000);
            setTimeout(() => {
                YangRAM.tools.playBgMusic('Unlock');
                Locker.off();
            }, 3000);
            setTimeout(() => {
                var time = (new Date().getTime() - lockTime) / 60000;
                System.Notifier.notice({
                    title: Runtime.locales.LOCKER.NOTICE.title,
                    content: Runtime.locales.LOCKER.NOTICE.content(time.toFixed(1))
                });
            }, 4000);
        },
        oncontactfailed = (data) => {
            //console.log(data);
            connecting(true);
            showTips(Locker.pincode, ' P I N ', 'FAILED');
        };

    var onbeforelock = () => {
            YangRAM.set({
                url: YangRAM.SubmitDIR + 'uoi/visa/lock/',
                data: {
                    word: 'bye-bye'
                },
                done: onlocked,
                fail: onlocked,
            });
        },
        onlocked = (data) => {
            //console.log(data);
            var preg = /^\[\{"reply":/;
            if (data.match(preg)) {
                var admin = JSON.parse(data)[0];
                if (admin.reply === 'see-you') {
                    Runtime.checkActivities(false);
                    lockTime = new Date().getTime();
                    onafterlock();
                }
            }
        },
        onafterlock = () => {
            YangRAM.tools.playBgMusic('Ring');
            YangRAM.bindListener('locker avatar', 'click', (event) => Locker.activeForm())
            Locker.mask.attr('status', '');
            Locker.avatar.attr('status', '');
            Locker.form.attr('status', '');
            pincode = '';
            Locker.pincode.val(pincode);
            Locker.on();
        };

    _.extend(Locker, true, {
        name: Runtime.locales.LOCKER.APPNAME,
        mask: YangRAM.$('masker', Locker.document),
        avatar: YangRAM.$('avatar', Locker.document),
        form: YangRAM.$('form', Locker.document),
        pincode: YangRAM.$('[name=pin]', this.document).attr('readonly', 'readonly'),
        tipsarea: YangRAM.$('pinshow', this.document),
        activeForm() {
            YangRAM.tools.playBgMusic('Unlock');
            this.mask.attr('status', 'actived');
            this.avatar.attr('status', 'actived');
            this.form.attr('status', 'actived');
            this.pincode.attr('placeholder', ' P I N ').focus();
            YangRAM.removeListener('locker avatar', 'click');
            Locker.timer && clearTimeout(Locker.timer);
            Locker.timer = setTimeout(() => {
                //console.log(Locker.timer);
                Locker.launch(true);
            }, 120000);
            return this;
        },
        launch(relock) {
            if (relock) {
                onafterlock();
            } else {
                onbeforelock();
            }
            return this;
        },
        listenEvents() {
            YangRAM.bindListener('locker input[name=pin]', 'keypress', checkpincodeOnKeyPress)
                .bindListener('locker input[name=pin]', 'keyup', checkpincodeOnKeyUp)
                .bindListener('locker input[name=pin]', 'input', checkpincodeOnChange);
            return this;
        }
    }).listenEvents();
});