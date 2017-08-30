System.DeclareModel('MessageModel', (YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Notifier = System.Notifier,
        Alerter = Notifier.Alerter;

    return declare({
        appid: 'YANGRAM',
        title: Runtime.locales.NOTIFIER.DEFAULT.title,
        content: Runtime.locales.NOTIFIER.DEFAULT.content,
        confirm: Runtime.locales.NOTIFIER.DEFAULT.confirm,
        resolve: Runtime.locales.NOTIFIER.DEFAULT.resolve,
        reject: Runtime.locales.NOTIFIER.DEFAULT.reject,
        cancel: Runtime.locales.NOTIFIER.DEFAULT.cancel,
        href: null,
        done: null,
        fail: null,
        undo: null,
        always: null,
        useTone: false,
        duration: 20000,
        _init(settings) {
            for (var i in settings) {
                if (settings[i] && this.__proto__.hasOwnProperty(i)) {
                    this[i] = settings[i];
                }
            }
            return this;
        },
        remain(list) {
            list = list || Notifier.AppNotice
            var html = '<icon><img src="' + YangRAM.RequestDIR + 'i/sources/icons/' + this.appid + '/60/"></icon><v><msgtit>' + this.title + '</msgtit><msgcon>' + this.content + '</msgcon><v>';
            var notice = YangRAM.create('message', list, {
                aid: this.appid,
                href: this.href,
                state: 'on',
                html: html
            });
            return this;
        },
        alert() {
            if (this.done && this.fail) {
                var buttons = '<click data-exec="resolve">' + this.resolve + '</click><click data-exec="reject">' + this.reject + '</click><click data-exec="pending">' + this.cancel + '</click>';
            } else if (this.done) {
                var buttons = '<click data-exec="confirm">' + this.confirm + '</click><click data-exec="cancel">' + this.cancel + '</click>';
            } else if (this.always) {
                var buttons = '<click data-exec="always">' + this.confirm + '</click>';
            } else {
                var buttons = '<click data-exec="notice">' + this.confirm + '</click>';
            }
            Alerter.push({
                handlers: {
                    done: this.done,
                    fail: this.fail,
                    undo: this.undo,
                    always: this.always
                },
                title: this.title,
                content: this.content,
                buttons: buttons
            });
            return this;
        },
        notice(remain) {
            var html = '<icon><img src="' + YangRAM.RequestDIR + 'i/sources/icons/' + this.appid + '/60/"></icon><v><msgtit>' + this.title + '</msgtit><msgcon>' + this.content + '</msgcon></v>'
            var notices = YangRAM.$('notice[state=off], notice[state=on]', Notifier.document);
            var position;
            if (notices.length > 0) {
                position = (parseInt(YangRAM.attr(notices.get(-1), 'posi')) + 1) % 8;
            } else {
                position = 0;
            }
            YangRAM.$('notice[posi="' + position + '"]').each(function() {
                Notifier.hide(this);
            });
            var notice = YangRAM.create('notice', Notifier.document, {
                aid: this.appid,
                href: this.href,
                state: 'off',
                posi: position,
                html: html
            });
            setTimeout(() => {
                YangRAM.attr(notice, 'state', 'on');
                if (this.Tone) {
                    YangRAM.tools.playBgMusic('Notify');
                }
            }, 500);
            setTimeout(() => {
                Notifier.hide(notice);
            }, this.duration);
            if (remain) {
                this.remain();
            }
            return this;
        }
    });
});