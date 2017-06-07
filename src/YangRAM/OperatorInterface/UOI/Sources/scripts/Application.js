System.DeclareModel('Application', (pandora, declare, global, undefined) => {
    var _ = pandora,
        document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        presetApps = Runtime.storage.presetApplications,
        installedApps = Runtime.storage.installedApplications,
        activedApps = Runtime.storage.activedApplications;

    //console.log(Runtime.storage.launchedApplications);

    var Interface = _.extend({}, System.ModuleSeeds.apis.Common(_, declare, global, undefined), {
        state: false,
        source: undefined,
        viewstatus: 0,
        onload: System.Empty,
        onclosedialog: System.Empty,
        _init(appid) {
            if (_.util.bool.isNum(appid) || _.util.arr.has(['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'], appid) !== false) {
                if (installedApps["APP-" + appid]) {
                    this.name = installedApps["APP-" + appid]['name'];
                } else if (presetApps["APP-" + appid]) {
                    this.name = presetApps["APP-" + appid]['name'];
                }
                this.appid = appid;
                this.author = installedApps["APP-" + appid] ? installedApps["APP-" + appid]['Author'] : System.YangRAM.author;
                this.version = installedApps["APP-" + appid] ? installedApps["APP-" + appid]['Version'] : System.YangRAM.version;
                this.layerIndex = --System.Workspace.appLayerIndexesMin;
                this.OIMLElement = new System.Workspace.OIMLElement(this);
                this.Element = this.OIMLElement.Element;
                this.document = this.OIMLElement.OIML;
                this.__dirs = {
                    main: System.YangRAM.URI + appid + '/',
                    getter: System.YangRAM.RequestDIR + appid + '/',
                    setter: System.YangRAM.SubmitDIR + appid + '/'
                }
            } else {
                var tagName = appid.toLowerCase();
                this.name = tagName.replace(/^\w/, (s) => s.toUpperCase());
                this.appid = tagName === 'explorer' ? tagName.toLowerCase() : tagName.toUpperCase();
                this.author = System.author;
                this.version = System.version;
                this.Element = document.getElementsByTagName(tagName).item(0);
                this.document = this.Element;
                this.$ = (selector) => { return System.YangRAM.$(selector, this.Element); }
                this.loadURI = null;
                if (tagName !== 'yangram') {
                    //this.URI = System.YangRAM.URI;
                    this.__dirs = {
                        main: System.YangRAM.URI + tagName + '/',
                        getter: System.YangRAM.RequestDIR + tagName + '/',
                        setter: System.YangRAM.SubmitDIR + tagName + '/'
                    }
                }
            }
            this.title = this.name;
            this.timeStamp = new Date().getTime();
            this.contextmenus = [];
            this.__temp = {
                url: 'default'
            };
            Runtime.storage.launchedApplications[this.appid] = this;
        },
        getBrothersList(callback) {
            return this;
        },
        callBrother(developid) {
            return this;
        },
        on() {
            this.state = true;
            this.attr('state', 'on').resize();
            return this;
        },
        off() {
            this.state = false;
            this.attr('state', 'off');
            return this;
        },
        togglePower() {
            if (this.state) {
                this.off();
            } else {
                this.on();
            }
        },
        setFullScreenView(keep) {
            this.attr('size', 'cover').viewstatus = 1;
            this.resize();
            keep || Runtime.resortApps(this.appid);
            return this;
        },
        setCenteredView(keep) {
            this.attr('size', 'center').viewstatus = 0;
            this.resize();
            keep || Runtime.resortApps(this.appid);
            return this;
        },
        setListModeView() {
            this.attr('size', 'listitem');
            if (!this.viewstatus) {
                this.viewstatus = 2;
            }
            System.YangRAM.setStyle(this.view, {
                minHeight: 'none'
            });
            this.resize();
            return this;
        },
        setSource(href) {
            this.source = href;
            System.YangRAM.$('windows>application[appid=' + this.appid + ']').attr('href', this.source);
            System.YangRAM.$('rankinglist application[appid=' + this.appid + ']').attr('href', this.source);
            return this;
        },
        mark(remark) {
            remark = _.util.bool.isStr(remark) ? remark : 'Editing';
            activedApps[this.appid] = remark;
            return this;
        },
        release() {
            if (activedApps[this.appid]) {
                activedApps[this.appid] = undefined;
                delete activedApps[this.appid];
            }
            return this;
        },
        setViewTop(top) {
            var elem = this.Element;
            var currst = elem.scrollTop;
            var prems = (top - elem.scrollTop) / 20;
            var times = 0;
            var timer = setInterval(() => {
                if (++times == 20) {
                    clearInterval(timer);
                    elem.scrollTop = top;
                } else {
                    elem.scrollTop += prems;
                };
            }, 10);
            return this;
        },
        toTop() {
            return this.setViewTop(0);
        },
        toBottom() {
            var view = this.$('view');
            var height = view.outerHeight(true);
            if (height > System.Height) {
                return this.setViewTop(System.Height - height);
            } else {
                return this.setViewTop(0);
            }
        },
        render(string) {
            this.OIMLElement.write(string, this.name);
            this.title = this.$('view').attr('title') || this.name;
            this.OIMLElement.writeInfo(this.title);
            this.topvision = this.$('view>top-vision')[0];
            this.OIMLElement.render();
            this.scrolls = this.OIMLElement.widgets.scrolls;

            if (this.viewstatus && this.view) {
                System.YangRAM.setStyle(this.view, {
                    minHeight: System.Workspace.appFullScreenHeightMin,
                });
            }
            System.ProcessBus.rescan();
            _.util.bool.isFn(this.onload) && this.onload();
            return this.resize();
        },
        open(href, callback, reload) {
            href ? href : this.source || 'default';
            if (!reload && (href == this.source)) {
                return false;
            }
            return this.loadURI(href, callback);
        },
        updateLocalArea(selector, content) {
            this.$(selector).html(this.OIMLElement.trim(content));
            System.Workspace.resize();
            return this;
        },
        onlaunch(href) {
            this.open(href);
            return this;
        },
        resize() {
            _.util.bool.isFn(this.onafterresize) ? this.onafterresize.call(this) : System.Empty();
            return this;
        },
        main() {
            global.console.log(this.appid + ': ' + Runtime.locales.COMMON.HELLO);
        },
        _dest() {
            global.console.log(this.appid + ': ' + Runtime.locales.COMMON.BYE_BYE);
        }
    });

    System.ModuleSeeds.apis.Common = undefined;

    return declare(_.data.Component, Interface);
});