System.ExtendsMethods((pandora, declare, global, undefined) => {
    var _ = pandora,
        document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime;

    return {
        $(selector) {
            return this.OIMLElement.$(selector);
        },
        regContextMenus(name, data) {
            if (parseInt(this.appid)) {
                name = 'A' + this.appid + '-' + name;
            }
            this.contextmenus.push(name);
            System.HiBar.Contexts.push(name, data);
            return this;
        },
        regHeadBar(data) {
            data = data || { appname: this.name };
            System.HiBar.TitleAndMenu.push(this.appid, data);
            System.YangRAM.$('[appid="' + Runtime.currentRunningAppID + '"]').attr('state', 'on').attr('running', '');
            return this;
        },
        browseWebPage(url) {
            System.Workspace.Browser.browse(url);
            return this;
        },
        get(url, options) {
            options.url = url ? this.__dirs.getter + url : this.__dirs.getter;
            System.GET(options);
            return this;
        },
        set(url, options) {
            options.url = url ? this.__dirs.setter + url : this.__dirs.setter;
            System.POST(options);
            return this;
        },
        upload(file, settings) {
            settings = settings || {};
            if (_.util.bool.isNum(settings.fldid) && settings.fldid >= 6) {
                var fldid = settings.fldid;
            } else if (parseInt(settings.fldid) && parseInt(settings.fldid) >= 6) {
                var fldid = settings.fldid;
            } else {
                var fldid = 0;
            }
            if (_.util.bool.isStr(settings.dir)) {
                var dir = settings.dir;
            } else {
                var dir = '';
            }
            System.Uploader.transfer(file, this.appid, {
                data: {
                    fldid: fldid,
                    dir: dir,
                    id: settings.id
                },
                handlers: {
                    before: settings.before,
                    progress: settings.progress,
                    after: settings.after,
                    done: settings.done,
                    fail: settings.fail
                },
                returnType: settings.returnType
            });
        },
        loadStyle(basename, callback) {
            callback = _.util.bool.isFn(callback) ? callback : System.Empty;
            callback = _.util.bool.isFn(basename) ? basename : callback;
            basename = _.util.bool.isStr(basename) ? basename : '';
            this.__stylesheetslink = System.HiddenArea.$('link[appid=' + this.appid + ']')[0];
            if (!this.__stylesheetslink) {
                this.__stylesheetslink = System.HiddenArea.create('css', { appid: this.appid });
            }
            this.__stylesheetslink.href = this.__dirs.main + 'resources/oiss/' + basename;
            this.__stylesheetslink.addEventListener('load', () => {
                callback();
            });
            return this;
        },
        launch(href) {
            if (YangRAM.API.APP.checkAppid(this.appid)) {
                if (!this.state) {
                    var welcomeURL = this.__dirs.main + 'resources/ss/';
                    var mainURL = this.__dirs.main + 'resources/os/';
                    System.GET({
                        url: welcomeURL,
                        done: (txt) => {
                            this.render(txt).on().regHeadBar().setSource('resources/ss/');
                            Runtime.resortApps(this.appid, true, true);
                            Runtime.checkAppOS.call(this, mainURL, href);
                        }
                    });
                    this.__temp.href = href || 'default';
                } else {
                    Runtime.resortApps(this.appid, true);
                    _.util.bool.isFn(this.onlaunch) && this.onlaunch(href);
                }
            } else {
                this.on();
            }
            return this;
        },
        loadURI(href, callback) {
            if (!href) {
                return false;
            }
            var url = this.__dirs.main + href;
            System.GET({
                url: url,
                done: (txt) => {
                    this.render(txt) //.Src = url;
                    this.setSource(href);
                    _.util.bool.isFn(callback) && callback();
                },
                fail: (txt) => {
                    console.log(url);
                    new System.Notifier.Message({
                        title: 'Application Error',
                        content: 'Something Wrong With This Appliction!',
                        confirm: "Close App",
                        cancel: "Ignore Err",
                        done: () => {
                            this.setSource('default');
                            System.YangRAM.API.APP.close(this.appid, true);
                        },
                        undo: () => {
                            var html = '<info><applang>' + Runtime.locales.CODE + '</applang>'
                            html += '<atitle>' + Runtime.locales.COMMON.UNKNOWN_MISTAKE + '</atitle></info>';
                            html += '<view error>' + txt + '</view>';
                            this.render(html);
                            if (this.viewstatus === 0) {
                                System.YangRAM.setStyle(this.view, {
                                    minHeight: 360,
                                });
                            }
                            this.resize();
                            this.setSource(href);
                        }
                    }).alert();
                }
            });
            return this;
        },
        refresh(callback) {
            return this.loadURI(this.source, callback);
        },
        refreshLocalArea(selector) {
            var local = this.$(selector);
            var href = local.attr('src');
            if (href && href != '') {
                System.GET({
                    url: this.__dirs.main + href,
                    done: (txt) => {
                        local.html(this.OIMLElement.trim(txt));
                        System.Workspace.resize();
                    }
                });
            };
            return this;
        },
        bindListener(selector, eventType, handler, data) {
            _.dom.events.remove(this.Element, eventType, selector, handler);
            _.dom.events.add(this.Element, eventType, selector, data, handler);
            return this;
        },
        removeListener(selector, eventType, handler) {
            _.dom.events.remove(this.Element, eventType, selector, handler);
            return this;
        },
        listenEvent(selector, eventType, handler, data) {
            var elem = this.Element,
                callback = function(data) {
                    handler.call(this, data);
                    _.dom.events.remove(elem, eventType, selector, callback);
                };
            _.dom.events.add(elem, eventType, selector, data, callback);
            return this;
        },
        listenEvents(events) {
            _.each(events, (selector, handlers) => {
                _.each(handlers, (eventType, handler) => {
                    this.bindListener(selector, eventType, handler);
                });
            });
            return this;
        }
    };
}, 'Common');