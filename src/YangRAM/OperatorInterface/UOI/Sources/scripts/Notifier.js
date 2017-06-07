System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Notifier = System.Notifier,
        Alerter = Notifier.Alerter,
        _ = System.Pandora;


    var Message = Notifier.Message = System.ModuleSeeds.models.MessageModel(YangRAM, declare, global, undefined);

    System.ModuleSeeds.models.MessageModel = undefined;

    _.extend(Notifier, true, {
        name: Runtime.locales.NOTIFIER.APPNAME,
        launch() {
            YangRAM.API.APP.sleep('SYSTEM-MODULES').attr(System.HiBar.Messager, 'type', 'reading');
            return this.on();
        },
        sleep() {
            YangRAM.attr(System.HiBar.Messager, 'type', 'start');
            return this.off();
        },
        build(callback) {
            this.off().listenEvents().resize().lister = YangRAM.create('content', this.document);
            Alerter.bind();
            this.MsgUnread = YangRAM.create('msgroup', this.lister, {
                name: 'Unread Messages'
            });
            this.EvnNotice = YangRAM.create('msgroup', this.lister, {
                name: 'Events Today'
            });
            this.AppNotice = YangRAM.create('msgroup', this.lister, {
                name: 'Apps Notice'
            });
            YangRAM.create('scrollbar', this.document, {
                type: 'vert',
                innerHTML: '<rail></rail><scrolldragger></scrolldragger>'
            });
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(this.document);
            return this.getUnreadMessages(callback);
        },
        alert(string, callback) {
            new Message({
                content: string,
                always: callback,
            }).alert();
            return this;
        },
        popup(settings) {
            new Message(settings).alert();
            return this;
        },
        notice(settings, remain) {
            if (_.util.bool.isStr(settings)) {
                var msgSet = {
                    content: settings
                }
            }
            if (_.util.bool.isObj(settings)) {
                var msgSet = settings
            }
            new Message(msgSet).notice(remain);
            return this;
        },
        remain(settings) {
            new Message(settings).remain();
            return this;
        },
        getUnreadMessages(callback) {
            YangRAM.get({
                url: this.__dirs.main + 'oimessages/notice/',
                done: (txt) => {
                    //console.log(txt);
                    YangRAM.$('myangram vision list[name=msg-trn]', YangRAM.Account).css('top', '-70px');
                    Notifier.scanEvents();
                    _.util.bool.isFn(callback) && callback();
                },
                fail: (txt) => {
                    console.log(txt, this.__dirs.main + 'oimessages/notice/');
                }
            });
            return this;
        },
        scanEvents() {
            var that = this;
            var today = YangRAM.$('kalendar list[events][today]');
            if (today.length > 0) {
                var day = today.html();
                var Notice = undefined;
                var title;
                var content;
                var href;
                YangRAM.$('kalendar events [day="' + day + '"]').each(function() {
                    title = YangRAM.$('title', this).html();
                    content = '[';
                    content += YangRAM.$('begin', this).html();
                    content += ' - ';
                    content += YangRAM.$('end', this).html();
                    content += '] ';
                    content += YangRAM.$('remark', this).html();
                    href = YangRAM.$('url', this).html();
                    Notice = that.Message({
                        appid: 'KALENDAR',
                        title: title,
                        content: content,
                        href: href,
                        useTone: true,
                    }).remain(that.EvnNotice);
                });
                Notice && setTimeout(() => {
                    Notice.notice();
                }, 30000);
                this.resize();
            }
            return this;
        },
        resize() {
            var that = this;
            YangRAM.setStyle(this.document, {
                height: System.Height - 40
            });
            Alerter.resize();
            setTimeout(() => {
                that.scrollBAR && that.scrollBAR.resize();
            }, 500);
            return this;
        },
        listenEvents() {
            var onhide = function(event) {
                var appid = YangRAM.attr(this, 'aid');
                var href = YangRAM.attr(this, 'href');
                if (href != '' && href != 'null' && href != 'undefined') {
                    YangRAM.API.APP.launch(appid, href);
                }
                Notifier.hide(this);
            };
            var onbeforeread = function(event) {
                var appid = YangRAM.attr(this, 'aid');
                var href = YangRAM.attr(this, 'href');
                if (href != '' && href != 'null' && href != 'undefined') {
                    YangRAM.API.APP.launch(appid, href);
                }
            };
            _.dom.events.add(Notifier.document, 'click', 'notice', null, onhide);
            _.dom.events.add(Notifier.lister, 'click', 'message', null, onbeforeread);
            return this;
        },
        hide(elem) {
            if (elem && elem.nodeType == 1) {
                YangRAM.attr(elem, 'state', 'sleep');
                setTimeout(() => {
                    try {
                        elem && elem.parentNode && elem.parentNode.removeChild(elem);
                        elem = undefined;
                    } catch (err) {
                        console.log(err);
                    };
                }, 500);
            }
        }
    });

    global.alert = (string, callback) => Notifier.alert(string, callback);
});