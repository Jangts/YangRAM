System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Element = System.Element,
        AppWindows = System.Workspace.Windows,
        _ = System.Pandora;

    var Instances = {},
        getUid = (appid) => {
            if (appid < 10) return 'APP-000' + appid;
            else if (appid < 100) return 'APP-00' + appid;
            else if (appid < 1000) return 'APP-0' + appid;
            else return 'APP-' + appid;
        },
        ScrollBAR = declare(_.see.BasicScrollBAR, {
            _init(elem) {
                this.Element = elem;
                this.build();
                var app = _.dom.closest(elem, 'application');
                if (app) {
                    this.protect = true;
                    this.brakings = [app];
                } else {
                    this.protect = false;
                }
                this.bind();
            },
            protect: false,
            build() {
                if (this.Element) {
                    _.dom.setStyle(this.Element, 'overflow', 'hidden');
                    this.document = _.dom.query('content', this.Element)[0];
                    this.vertical = _.dom.query('scrollbar[type=vert]', this.Element)[0];
                    this.horizontal = _.dom.query('scrollbar[type=hori]', this.Element)[0];
                    if (this.vertical) {
                        this.verticalRail = _.dom.query.byTag('rail', this.vertical)[0];
                        this.verticalDragger = _.dom.query.byTag('scrolldragger', this.vertical)[0];
                    }
                    if (this.horizontal) {
                        this.horizontalRail = _.dom.query.byTag('rail', this.horizontal)[0];
                        this.horizontalDragger = _.dom.query.byTag('scrolldragger', this.horizontal)[0];
                    }
                    YangRAM.setStyle(this.document, {
                        display: 'block'
                    });
                    return this;
                }
                return null;
            }
        }),
        OIMLTabs = declare(_.see.Tabs.TabViews, {
            trigger: 'click',
            _init(elem, settings) {
                this.Element = elem;
                this.options = _.dom.query('tab-vision list', this.Element)[0];
                this.sections = _.dom.query('tab-sections', this.Element)[0];
                this.build(settings);
                this.bind();
            },
            create(tabName, tabAlias) {
                this.tabs[tabName] = {
                    option: tabName === this.startTabName ? YangRAM.create('item', this.options, {
                        dataTabName: tabName,
                        starttab: '',
                        html: '<v>' + (tabAlias || tabName) + '</v>'
                    }) : YangRAM.create('item', this.options, {
                        dataTabName: tabName,
                        html: '<v>' + (tabAlias || tabName) + '</v><el>×</el>'
                    }),
                    section: YangRAM.create('section', this.sections, {
                        dataTabName: tabName
                    }),
                    tag: {
                        origin: '',
                        trimed: '',
                        path: []
                    }
                }
                return this.resize();
            },
            write(tabName, tag, txt) {
                this.tabs[tabName].section.innerHTML = System.Workspace.OIMLElement.trim(txt);
                return this.cutTo(tabName, tag);
            },
            resize() {
                _.dom.setStyle(this.options, {
                    width: _.dom.select('item[data-tab-name]', this.options).widths() + 1
                });
                return this;
            },
            onoptionclick(option, target) {
                var tabName = YangRAM.attr(option, 'data-tab-name').toUpperCase();
                if (target.tagName == 'V') {
                    this.cutTo(tabName);
                }
                if (target.tagName == 'EL') {
                    new System.Notifier.Message({
                        title: 'Confirm Close?',
                        content: 'You are trying to close a tab, are you sure to do this?',
                        resolve: "Close",
                        reject: "Cut To",
                        cancel: "Cancel",
                        done: () => {
                            this.destroy(tabName);
                        },
                        fail: () => {
                            this.cutTo(tabName);
                        }
                    }).alert();
                }
            }
        });

    System.Workspace.OIMLElement = declare({
        ID: 0,
        OIML: null,
        Element: null,
        widgets: null,
        _init(app) {
            var id = getUid(app.appid);
            if (Instances[id] == undefined) {
                this.ID = id;
                this.Element = YangRAM.create('application', AppWindows, {
                    id: id,
                    appid: app.appid,
                    layerIndex: app.layerIndex,
                    state: 'launch',
                    scroll: '',
                    src: 'main',
                    runstatus: 'aftersleeping',
                    html: '<mask></mask>'
                });
                this.OIML = YangRAM.create('oiml', this.Element, {
                    style: {
                        'width': System.Workspace.appWidth,
                        'min-width': System.Workspace.appWidth
                    },
                    type: System.Workspace.AppLeft ? 'right-status' : 'full-status'
                });
                this.widgets = {};
                Instances[id] = this;
            }
            this.app = app;
        },
        $(selector) {
            return _.dom.select(selector, this.Element);
        },
        write(string, title) {
            var html = this.trim(string);
            this.OIML.innerHTML = html;
            this.view = this.$('view')[0];
            if (this.view) {
                this.app.view = this.view;
                return this;
            }
            this.OIML.innerHTML = '';
            return this.writeView(html);
        },
        writeView(html) {
            new System.Notifier.Message({
                title: 'Application Error',
                content: 'Something Wrong With This Appliction!',
                confirm: "Close App",
                cancel: "Ignore Err",
                done: () => {
                    System.YangRAM.API.APP.close(this.appid, true);
                },
                undo: () => {
                    this.app.view = this.view = YangRAM.create('view', this.OIML, {
                        error: 'error',
                        html: html
                    });
                }
            }).alert();
            return this;
        },
        writeInfo(title) {
            var atitle = this.$('info atitle');
            if (atitle[0]) {
                if (!atitle.html()) {
                    atitle.html(title)
                }
            } else {
                var info = this.$('info');
                if (info[0]) {
                    info.html('<atitle>' + title + '</atitle>');
                } else {
                    YangRAM.create('info', this.OIML, { html: '<atitle>' + title + '</atitle>' });
                }
            }
            return this;
        },
        create(type, context) {
            return null;
        },
        createForm(elem) {
            new _.form.Data(elem);
        },
        render() {
            return this.renderScrollBars();
        },
        renderScrollBars() {
            var ScrollBars = [];
            this.$('[x-scrollbar=true], [y-scrollbar=true]').each(function() {
                this.innerHTML = '<content>' + this.innerHTML + '</content>';
            });
            this.$('[x-scrollbar=true]').append('<scrollbar type="hori"><rail></rail><scrolldragger></scrolldragger></scrollbar>')
            this.$('[y-scrollbar=true]').append('<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>');
            this.$('[x-scrollbar=true], [y-scrollbar=true]').each(function() {
                ScrollBars.push(new ScrollBAR(this));
            });
            ScrollBars.resize = () => {
                setTimeout(() => {
                    for (var i = 0; i < ScrollBars.length; i++) {
                        ScrollBars[i].resize();
                    }
                }, 100);
            }
            this.widgets.scrolls = ScrollBars;
            return this;
        },
        renderTabs(elem, settings) {
            settings = settings || { startt }
            return new OIMLTabs(elem, settings);
        },
        renderSliders() {

        },
        bind(type, target) {
            if (target) {
                switch (type) {
                    case 'scrollbar':
                        return new ScrollBAR(target);
                }
            }
            return null;
        },
        trim: System.TrimHTML
    });

    _.extend(System.Workspace.OIMLElement, true, {
        trim: System.TrimHTML,
        renderScrollBAR(elem) {
            if (_.util.bool.isEl(elem)) {
                return new ScrollBAR(elem);
            }
            return null;
        },
        destroy(appid) {
            var id = getUid(appid);
            Instances[id] = undefined;
        }
    });

    YangRAM.bindListener('list[type=menu] group itit', 'click', function() {
        if (YangRAM.attr(this.parentNode, 'opened') === 'opened') {
            YangRAM.attr(this.parentNode, 'opened', 'false');
        } else {
            YangRAM.attr(this.parentNode, 'opened', 'opened');
        }
        //console.log('foo');
    });
});