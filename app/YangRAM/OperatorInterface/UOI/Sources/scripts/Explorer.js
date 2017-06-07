System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Explorer = System.Explorer,
        _ = System.Pandora;

    var bindListeners = {
        'vision.explorer-header .explorer-swch': {
            'click' () {
                Explorer.hide();
            }
        },
        'vision.explorer-content content .item.folder': {
            'click' (event) {
                Explorer.open(YangRAM.attr(this, 'href'), true);
            }
        },
        'vision.explorer-content content list item': {
            'click' (event) {
                Explorer.open(YangRAM.attr(this, 'href'), true);
            }
        },
        'nav.explorer-nav .explorer-prev': {
            'click' (event) {
                if (Explorer.Flds.length > 0) {
                    Explorer.open(Explorer.Flds.pop(), false);
                }
            }
        },
        'nav.explorer-nav .turn-to-set': {
            'click' (event) {
                Explorer.$('nav.explorer-nav click').toggleClass('selected', false);
                Explorer.$('nav.explorer-nav .turn-to-set').toggleClass('selected', true);
                Explorer.open('spc/', true);
            }
        },
        'nav.explorer-nav .turn-to-lib': {
            'click' (event) {
                Explorer.$('nav.explorer-nav click').toggleClass('selected', false);
                Explorer.$('nav.explorer-nav .turn-to-lib').toggleClass('selected', true);
                Explorer.open('src/all/5', true);
            }
        },
        'nav.explorer-nav .turn-to-cst': {
            'click' (event) {
                Explorer.$('nav.explorer-nav click').toggleClass('selected', false);
                Explorer.$('nav.explorer-nav .turn-to-cst').toggleClass('selected', true);
                Explorer.open('csts/', true);
            }
        },
        '.explorer-content content .item .sele': {
            'click' (event) {
                var type = YangRAM.attr(this.parentNode, 'type');
                if (type != 'folder') {
                    YangRAM.toggleClass(this.parentNode, 'selected');
                }
            }
        },
        '.explorer-control .sele-all': {
            'click' (event) {
                Explorer.$('.explorer-content content .item').toggleClass('selected', true);
            }
        },
        '.explorer-control .sele-none': {
            'click' (event) {
                Explorer.$('.explorer-content content .item').toggleClass('selected', false);
            }
        },
        '.explorer-control .ins-contents': {
            'click' (event) {
                var items = [];
                var Handler = 'INF';
                var selects = Explorer.$('.explorer-content content .item.selected');
                var type = selects.attr('type');
                if (type == 'set') {
                    selects.each(function() {
                        items.push({
                            id: YangRAM.attr(this, 'x-id'),
                            title: YangRAM.$('.name', this).html(),
                            itemPreset: YangRAM.attr(this, 'preset'),
                            itemBaseType: YangRAM.attr(this, 'x-base'),
                            itemCatname: YangRAM.attr(this, 'itemname'),
                            modtime: YangRAM.$('.time', this).html()
                        });
                    });
                }
                if (items.length) {
                    if (parseInt(Runtime.currentRunningAppID)) {
                        var app = Runtime.application();
                        app.handlers && app.API.Explorer && _.util.bool.isFn(app.API.Explorer.SPC) && app.API.Explorer.SPC.call(app, items);
                    }
                } else {
                    alert('No Content Been Selected!');
                }
            }
        },
        '.explorer-control .ins-files': {
            'click' (event) {
                var items = [];
                Explorer.$('.explorer-content content .item.selected').each(function() {
                    var type = YangRAM.attr(this, 'type');
                    if (type != 'folder' && type != 'set') {
                        var id = YangRAM.attr(this, 'x-id');
                        var sfix = YangRAM.attr(this, 'suffix');
                        items.push({
                            name: YangRAM.attr(this, 'name'),
                            type: type,
                            src: YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + sfix,
                            size: YangRAM.$('.size', this).html(),
                            modtime: YangRAM.$('.time', this).html()
                        });
                    }
                });
                if (items.length > 0) {
                    if (parseInt(Runtime.currentRunningAppID)) {
                        var app = Runtime.application();
                        app.handlers && app.API.Explorer && _.util.bool.isFn(app.API.Explorer.SRC) && app.API.Explorer.SRC.call(app, items);
                    }
                } else {
                    alert('No File Been Selected!');
                }
            }
        },
    };

    _.extend(Explorer, true, {
        name: Runtime.locales.EXPLORER.MINI.APPNAME,
        Main: undefined,
        Flds: [],
        build() {
            var width = 640;
            var height = 460;
            var left = (System.Width - width) / 2;
            var top = (System.Height - height) / 2;
            left = left >= 0 ? left : 0;
            left = left <= System.Width - 30 ? left : System.Width - 30;
            top = top >= 0 ? top : 0;
            top = top <= System.Height - 30 ? top : System.Height - 30;
            this.document.innerHTML = '<vision class="explorer-header"><el class="explorer-name">' + this.name + '</el><el class="explorer-swch">×</el></vision>'
            _.dom.setStyle(this.document, {
                width: width,
                height: height,
                top: top,
                left: left
            });
            var mainHeight = height - 130;
            this.Nav = YangRAM.create('nav', this.document, {
                className: 'explorer-nav',
                html: '<el class="explorer-prev">←</el><click class="turn-to-set">' + Runtime.locales.EXPLORER.MINI.CLASSES["Preset Content"] + '</click><click class="turn-to-lib selected">' + Runtime.locales.EXPLORER.MINI.CLASSES["Resources Library"] + '</click><click class="turn-to-cst">' + Runtime.locales.EXPLORER.MINI.CLASSES["Custom Content"] + '</click>'
            });
            this.controlarea = YangRAM.create('vision', this.document, {
                className: 'explorer-control',
                html: '<click class="sele-all">' + Runtime.locales.EXPLORER.MINI.OPERATIONS["Select All"] + '</click><click class="sele-none">' + Runtime.locales.EXPLORER.MINI.OPERATIONS["Unselect"] + '</click><click class="ins-files">' + Runtime.locales.EXPLORER.MINI.OPERATIONS["Insert Files"] + '</click><click class="ins-contents">' + Runtime.locales.EXPLORER.MINI.OPERATIONS["Insert Contents"] + '</click>'
            });
            var main = YangRAM.create('vision', this.document, {
                className: 'explorer-content',
                style: {
                    width: width - 20,
                    height: mainHeight,
                },
                html: '<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>'
            })
            this.content = YangRAM.create('content', main);
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(main);
            return this.listenEvents();
        },
        launch() {
            if (this.content && (this.content.innerHTML == '')) {
                this.open('src/all/5', true);
            } else {
                this.on();
            }
            return this;
        },
        open(url, isRecord) {
            YangRAM.get({
                url: YangRAM.URI + 'explorer/' + url,
                done: (txt) => {
                    this.content.innerHTML = txt;
                    if (isRecord && this.current) {
                        this.Flds.push(this.current);
                    }
                    this.current = url;
                    this.on().scrollBAR.resize();
                }
            });
            return this;
        },
        Hide: Explorer.off,
        listenEvents() {
            var dragstatus = false;
            var dragstartX = 0;
            var dragstartY = 0;
            var Events = new _.dom.Events(window)
                .push('mousemove', null, null, (event) => {
                    if (dragstatus) {
                        if (event.x != dragstartX) {
                            var left = parseInt(_.dom.getStyle(Explorer.document, 'left')) + event.x - dragstartX;
                            left = left >= 0 ? left : 0;
                            left = left <= System.Width - 30 ? left : System.Width - 30;
                            _.dom.setStyle(Explorer.document, 'left', left);
                            dragstartX = event.x;
                        }
                        if (event.y != dragstartY) {
                            var top = parseInt(_.dom.getStyle(Explorer.document, 'top')) + event.y - dragstartY;
                            top = top >= 0 ? top : 0;
                            top = top <= System.Height - 30 ? top : System.Height - 30;
                            _.dom.setStyle(Explorer.document, 'top', top);
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
                .bindListener('vision.explorer-header', 'mousedown', (event) => {
                    dragstatus = true;
                    dragstartX = event.x;
                    dragstartY = event.y;
                })
                .bindListener('vision.explorer-header .explorer-swch', 'click', bindListeners['vision.explorer-header .explorer-swch']['click'])
                .bindListener('vision.explorer-content content .item.folder', 'click', bindListeners['vision.explorer-content content .item.folder']['click'])
                .bindListener('vision.explorer-content content list item', 'click', bindListeners['vision.explorer-content content list item']['click'])
                .bindListener('nav.explorer-nav .explorer-prev', 'click', bindListeners['nav.explorer-nav .explorer-prev']['click'])
                .bindListener('nav.explorer-nav .turn-to-set', 'click', bindListeners['nav.explorer-nav .turn-to-set']['click'])
                .bindListener('nav.explorer-nav .turn-to-lib', 'click', bindListeners['nav.explorer-nav .turn-to-lib']['click'])
                .bindListener('nav.explorer-nav .turn-to-cst', 'click', bindListeners['nav.explorer-nav .turn-to-cst']['click'])
                .bindListener('.explorer-content content .item .sele', 'click', bindListeners['.explorer-content content .item .sele']['click'])
                .bindListener('.explorer-control .sele-all', 'click', bindListeners['.explorer-control .sele-all']['click'])
                .bindListener('.explorer-control .sele-none', 'click', bindListeners['.explorer-control .sele-none']['click'])
                .bindListener('.explorer-control .ins-contents', 'click', bindListeners['.explorer-control .ins-contents']['click'])
                .bindListener('.explorer-control .ins-files', 'click', bindListeners['.explorer-control .ins-files']['click']);
        }
    }).build();
});