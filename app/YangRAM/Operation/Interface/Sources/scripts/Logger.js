System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Logger = System.Logger,
        _ = System.Pandora;

    var showTips = (obj, str, _str) => {
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
                Logger.pincode.removeClass('connecting').attr('placeholder', ' P I N ');
            } else {
                Logger.pincode.addClass('connecting');
                Logger.pincode.attr('placeholder', 'CONNECT');
                var m, s;
                ctimer = setInterval(function() {
                    ctimes++;
                    m = ctimes % 6;
                    s = m < 3 ? m : 6 - m;
                    Logger.pincode.attr('placeholder', 'CONNECTING'.substr(s, 7));
                }, 500);
            }
        };

    var pincode = '',
        checkpincodeOnKeyUp = (e) => {
            if (e.which === 8 && pincode.length > 0) {
                pincode = pincode.slice(0, pincode.length - 1);
                Logger.pincode.val(pincode.replace(/\w/g, '*'));
            }
        },
        checkpincodeOnChange = (e) => {
            Logger.pincode.val(pincode);
        },
        checkpincodeOnKeyPress = (e) => {
            var keyName = e.keyName;
            if (/^\d$/.test(keyName)) {
                pincode += keyName;
                Logger.tipsarea.html(keyName).show();
                setTimeout(() => {
                    if (pincode.length === 6) {
                        Logger.pincode.val('');
                        var checkResult = checkInput();
                        if (checkResult) {
                            checkContact(pincode);
                            pincode = '';
                        }
                    } else {
                        Logger.pincode.val(pincode.replace(/\w/g, '*'));
                    }
                }, 0);
            } else {
                Logger.tipsarea.html('Err').show();
                setTimeout(() => {
                    Logger.pincode.val(pincode);
                }, 0);
            }
            setTimeout(() => {
                Logger.tipsarea.hide();
            }, 1500);
        };

    var checkInput = () => {
        if (Logger.username.val().length == 0) {
            showTips(Logger.username, Runtime.locales.LOGGER.USERNAME, Runtime.locales.LOGGER.USERNAME_CANNOT_EMPTY);
        } else if (Logger.password.val().length < 8) {
            showTips(Logger.password, Runtime.locales.LOGGER.PASSWORD, Runtime.locales.LOGGER.PASSWORD_MUST_MORE_THEN(8));
        } else {
            return true;
        }
        return false;
    };

    var logurl = Runtime.runInDesktop ? YangRAM.SubmitDIR + System.ID + '/visa/logondesktop/' : YangRAM.SubmitDIR + System.ID + '/visa/logon/';

    var checkContact = (pincode) => {
            YangRAM.set({
                url: logurl,
                data: {
                    username: Logger.username.val(),
                    password: Logger.password.val(),
                    pin: pincode
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
                if (admin.username === Logger.username.val()) {
                    showTips(Logger.pincode.blur(), ' P I N ', 'CHECKED');
                    return Logger.onlogon(admin);
                }
            }
            if (data === '[{"error":"PIN_ERROR"}]') {
                showTips(Logger.pincode.focus(), ' P I N ', 'PIN_ERR');
                return;
            }
            if (data === '[{"error":"INPUTS_ERROR"}]') {
                showTips(Logger.pincode.blur(), ' P I N ', 'ILLEGAL');
                showTips(Logger.username.focus(), Runtime.locales.LOGGER.USERNAME, Runtime.locales.LOGGER.USERNAME_OR_PASSWORD_NOT_MATCH);
                showTips(Logger.password, Runtime.locales.LOGGER.PASSWORD, Runtime.locales.LOGGER.USERNAME_OR_PASSWORD_NOT_MATCH);
                return;
            }
            showTips(Logger.pincode, ' P I N ', 'PAS_ERR');
            showTips(Logger.username.focus(), Runtime.locales.LOGGER.USERNAME, Runtime.locales.LOGGER.USERNAME_OR_PASSWORD_NOT_MATCH);
            showTips(Logger.password, Runtime.locales.LOGGER.PASSWORD, Runtime.locales.LOGGER.USERNAME_OR_PASSWORD_NOT_MATCH);
            return;
        },
        oncontactfailed = (data) => {
            connecting(true);
            showTips(Logger.pincode, ' P I N ', 'FAILED');
            Logger.username.focus();
        };

    var checkLogout = () => {
            var result = Runtime.checkActivities();
            if (result) {
                new System.Notifier.Message({
                    title: 'Applications Still In Editing',
                    content: result,
                    confirm: "Still Log Out",
                    cancel: "Cancel",
                    done: onbeforelogoff
                }).alert();
            } else {
                onbeforelogoff();
            }
        },
        onbeforelogoff = () => {
            YangRAM.set({
                url: YangRAM.SubmitDIR + 'uoi/visa/logoff/',
                data: {
                    word: 'bye-bye'
                },
                done: onlogoff,
                fail: onlogoff,
            });
        },
        onlogoff = (data) => {
            //console.log(data);
            var preg = /^\[\{"reply":/;
            if (data.match(preg)) {
                var admin = JSON.parse(data)[0];
                if (admin.reply === 'see-you') {
                    Runtime.checkActivities(false);
                    YangRAM.tools.playBgMusic('Logout');
                    Logger.Loadvision.html('SEE YOU');
                    Logger.on().loadingstatus.attr('status', 'off');
                    setTimeout(() => {
                        System.OnLogoff();
                    }, 1000);
                }
            }
        };

    YangRAM.extends(Logger, true, {
        name: Runtime.locales.LOGGER.APPNAME,
        build() {
            this.avatar = YangRAM.$('avatar', this.document);
            this.form = YangRAM.$('form', this.document);
            this.username = YangRAM.$('[name=opn]', this.document);
            this.password = YangRAM.$('[name=opp]', this.document);
            this.pincode = YangRAM.$('[name=pin]', this.document).attr('readonly', 'readonly');
            this.tipsarea = YangRAM.$('pinshow', this.document);
            this.Loadvision = YangRAM.$('percent-vision', this.avatar[0]);
            this.loadedpercent = YangRAM.$('el', this.avatar[0]);
            this.loadingstatus = YangRAM.$('status-vision', this.document);
        },
        logoff() {
            checkLogout();
            return this;
        },
        sleep() {
            YangRAM.tools.playBgMusic('Loaded');
            this.off().loadingstatus.attr('status', 'off');
            return this;
        },
        listenEvents() {
            if (Logger.activeForm) {
                YangRAM.bindListener('logger avatar', 'click', (event) => Logger.activeForm());
            }
            YangRAM.bindListener('logger input[name=pin]', 'keypress', checkpincodeOnKeyPress)
                .bindListener('logger input[name=pin]', 'keyup', checkpincodeOnKeyUp)
                .bindListener('logger input[name=pin]', 'input', checkpincodeOnChange);
            return this;
        }
    }).build();
});