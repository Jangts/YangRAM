System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Dialog = System.Dialog,
        _ = System.Pandora;

    var oissFileBasename,
        getPosition = (setttings) => {
            var width = setttings.width || 540;
            var height = setttings.height || 360;
            width = width < System.Width ? width : System.Width;
            height = height < System.Height ? height : System.Height;
            var left = setttings.left || (System.Width - width) / 2 - 2;
            var top = setttings.top || (System.Height - height) / 2 - 2;
            left = left >= 0 ? left : 0;
            left = left <= System.Width - 30 ? left : System.Width - 30;
            top = top >= 0 ? top : 0;
            top = top <= System.Height - 30 ? top : System.Height - 30;
            return { width, height, top, left };
        };

    _.extend(Dialog, true, {
        name: Runtime.locales.DIALOG.APPNAME,
        content: undefined,
        build(setttings) {
            setttings = setttings || {};
            oissFileBasename = setttings.css || dialog;
            var appid = setttings.appid || this.appid;
            this.Element.innerHTML = '';
            this.currentappid = appid;
            return this.create(appid, setttings, getPosition(setttings));
        },
        create(appid, setttings, styles) {
            var title = setttings.title || 'System Dialog';
            this.document = YangRAM.create('vision', this.Element, {
                className: 'dialog-main-box',
                style: {
                    width: styles.width,
                    height: styles.height,
                    top: styles.top,
                    left: styles.left
                },
                html: '<vision class="dialog-header"><el class="dialog-name">' + title + '</el><el class="dialog-swch">×</el></vision>'
            });
            if (setttings.control) {
                var mainHeight = styles.height - 120;
                this.buildControl(appid, setttings);
            } else {
                var mainHeight = styles.height - 61;
            }
            var app = YangRAM.create('application', this.document, {
                appid: appid,
                style: {
                    width: styles.width - 20,
                    height: mainHeight,
                },
                html: '<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar><scrollbar type="hori"><rail></rail><scrolldragger></scrolldragger></scrollbar>'
            });
            this.contentarea = YangRAM.create('content', app);
            this.contentarea.bindListener = function(selector, eventType, handler, data) {
                _.dom.events.add(Dialog.contentarea, eventType, selector, data, handler);
                return this;
            }
            this.contentarea.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(app);
            return this;
        },
        buildControl(appid, setttings) {
            var html = '';
            for (var i = 0; i < setttings.control.length; i++) {
                html += '<click href="trigger://' + appid + '::';
                html += setttings.control[i].href;
                html += '" args="' + setttings.control[i].Args + '">';
                html += setttings.control[i].name;
                html += '</click>';
            }
            this.controlarea = YangRAM.create('vision', this.document, {
                className: 'dialog-control',
                html: html
            });
            return this;
        },
        show(callback) {
            this.contentarea || this.build();
            if (oissFileBasename) {
                var link = YangRAM.$('link', this.Element)[0];
                if (!link) {
                    link = YangRAM.create('link', this.Element, {
                        type: 'text/css',
                        rel: 'stylesheet'
                    });
                }
                link.href = YangRAM.URI + this.currentappid + '/resources/oiss/' + oissFileBasename + '/';
                oissFileBasename = undefined;
                link.addEventListener('load', () => {
                    _.util.bool.isFn(callback) ? callback.call(Dialog) : Dialog.render(callback);
                });
            } else {
                _.util.bool.isFn(callback) ? callback.call(Dialog) : Dialog.render(callback);
            }
            return this.on();
        },
        render(content) {
            content = content || '';
            this.contentarea.innerHTML = System.TrimHTML(content);
            setTimeout(() => {
                Dialog.contentarea.scrollBAR.resize()
            }, 0);
            return this;
        },
        hide() {
            _.util.bool.isFn(Runtime.application(Dialog.currentappid).onclosedialog) && Runtime.application(Dialog.currentappid).onclosedialog(Dialog.contentarea);
            _.dom.events.remove(Dialog.contentarea);
            Dialog.Element.innerHTML = '';
            Dialog.document = undefined;
            Dialog.contentarea.scrollBAR = undefined;
            Dialog.contentarea = undefined;
            Dialog.off();
            oissFileBasename = undefined;
        },
        listenEvents() {
            var dragstatus = false;
            var dragstartX = 0;
            var dragstartY = 0;
            var Events = new _.dom.Events(global)
                .push('mousemove', null, null, (event) => {
                    if (dragstatus) {
                        if (event.x != dragstartX) {
                            var left = parseInt(_.dom.getStyle(Dialog.document, 'left')) + event.x - dragstartX;
                            left = left >= 0 ? left : 0;
                            left = left <= System.Width - 30 ? left : System.Width - 30;
                            //console.log(left);
                            _.dom.setStyle(Dialog.document, 'left', left);
                            dragstartX = event.x;
                        }
                        if (event.y != dragstartY) {
                            var top = parseInt(_.dom.getStyle(Dialog.document, 'top')) + event.y - dragstartY;
                            top = top >= 0 ? top : 0;
                            top = top <= System.Height - 30 ? top : System.Height - 30;
                            _.dom.setStyle(Dialog.document, 'top', top);
                            dragstartY = event.y;
                        }
                    }
                })
                .push('mouseup', null, null, (event) => {
                    if (dragstatus) {
                        dragstatus = false;
                        dragstartX = 0;
                        dragstartY = 0;
                    }
                });
            return this
                .bindListener('vision.dialog-header', 'mousedown', (event) => {
                    dragstatus = true;
                    dragstartX = event.x;
                    dragstartY = event.y;
                })
                .bindListener('vision.dialog-header .dialog-swch', 'click', () => {
                    Dialog.hide();
                });
        }
    }).listenEvents();
});