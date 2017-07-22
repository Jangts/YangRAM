System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Workspace = System.Workspace,
        Browser = Workspace.Browser,
        launchedApps = Runtime.storage.launchedApplications,
        _ = System.Pandora;

    var getCurrentRuntimeAppID = () => {
            if (_.util.arr.has(['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'], Runtime.currentRunningAppID) !== false) {
                return Runtime.currentRunningAppID;
            }
            return parseInt(Runtime.currentRunningAppID) || 'I4PLAZA';
        },
        getAppId = (elem) => {
            var node = _.dom.closest(elem, 'application');
            if (node) {
                return YangRAM.API.APP.getAppidByElement(node);
            } else {
                return getCurrentRuntimeAppID();
            }
        },
        Pageview = declare({
            appid: 0,
            state: false,
            Viewer: null,
            frame: null,
            histories: null,
            nextIndex: 0,
            _init(appid) {
                this.appid = appid;
                this.Element = YangRAM.create('application', Browser.document, {
                    appid: appid,
                    state: 'on',
                    running: '',
                    style: {
                        height: Workspace.appFullScreenHeightMin + 5
                    }
                });
                this.frame = YangRAM.create('iframe', this.Element, {
                    width: '100%',
                    height: '100%'
                });
                this.histories = [];
            },
            src: null,
            push(url) {
                url = url.replace(/^https*:/, '').replace(/\/+$/, '');
                if (url !== this.src) {
                    this.src = url;
                    this.histories.length = this.nextIndex;
                    this.histories.push(url);
                    this.nextIndex = this.histories.length;
                }
                return this;
            },
            loadURL(url, isnew) {
                this.launch().frame.src = url;
                if (isnew === true) {
                    this.push(url);
                }
                var that = this,
                    frameSize;
                this.frame.onload = function() {
                    YangRAM.setStyle(this.parentNode, { overflowY: 'hidden' }).setStyle(this, {
                        height: '100%',
                        overflow: 'auto'
                    });
                    try {
                        var document = this.contentDocument || this.contentWindow.document,
                            frameSize = _.dom.getSize(document.body);
                        url = document.URL;
                        //console.log(url, frameSize);
                    } catch (error) {
                        this.scrolling = 'auto';
                        if (global._current_loaded_frame_src) {
                            url = global._current_loaded_frame_src;
                            if (isnew === true && url) {
                                that.push(url);
                                //console.log(that.histories);
                            }
                            global._current_loaded_frame_src = null;
                        }
                        isnew = true;
                        return;
                    }
                    if (isnew === true) {
                        that.push(url);
                    }
                    isnew = true;
                    YangRAM.setStyle(this, {
                        height: frameSize.height,
                        overflow: 'hidden'
                    }).setStyle(this.parentNode, { overflowY: 'auto' });
                };
                this.frame.onchange = function() {
                    console.log([this]);
                }
                this.frame.onhashchange = function() {
                    console.log([this], that.frame.location, that.frame.location.hash);
                }
                YangRAM.setStyle(this.Element, { overflowY: 'hidden' }).setStyle(this.frame, {
                    height: '100%',
                    overflow: 'auto'
                });
                return this;
            },
            loadPrev() {
                if (this.nextIndex > 1) {
                    this.nextIndex--;
                    this.loadURL(this.histories[this.nextIndex - 1])
                }
                return this;
            },
            refresh() {
                return this.loadURL(this.histories[this.nextIndex - 1]);
            },
            loadNext() {
                if (this.nextIndex < this.histories.length) {
                    this.nextIndex++;
                    this.loadURL(this.histories[this.nextIndex - 1])
                }
                return this;
            },
            loadText(html) {
                this.launch().frame.srcdoc = html;
                return this;
            },
            launch() {
                this.state = true;
                YangRAM.attr(this.Element, 'state', 'on');
                return this;
            },
            sleep() {
                this.state = false;
                YangRAM.attr(this.Element, 'state', 'off');
                return this;
            },
            resize() {
                YangRAM.setStyle(this.Element, {
                    height: Workspace.appFullScreenHeightMin + 5
                });
                return this;
            }
        });

    var events = {
        'hibar subbars browsertools closebrowser': {
            'click' (event) {
                Browser.sleep(getCurrentRuntimeAppID());
            }
        },
        'hibar subbars browsertools browseprev': {
            'click' (event) {
                var appid = getCurrentRuntimeAppID();
                if (Browser.pageviews[appid]) {
                    Browser.launch().pageviews[appid].loadPrev();
                }
            }
        },
        'hibar subbars browsertools refresh': {
            'click' (event) {
                var appid = getCurrentRuntimeAppID();
                if (Browser.pageviews[appid]) {
                    Browser.launch().pageviews[appid].refresh();
                }
            }
        },
        'hibar subbars browsertools browsenext': {
            'click' (event) {
                var appid = getCurrentRuntimeAppID();
                if (Browser.pageviews[appid]) {
                    Browser.launch().pageviews[appid].loadNext();
                }
            }
        },
        'click[href]': {
            'click' (event) {
                var href = YangRAM.attr(this, 'href');
                var readonly = YangRAM.attr(this, 'readonly');
                if (href && readonly != 'true') {
                    if (href.match(/(:\/\/|::)/)) {
                        var arr = href.split('://');
                        if (arr.length == 2) {
                            var protocol = arr[0].toUpperCase();
                            var href = arr[1];
                        } else {
                            var protocol = 'LAUNCH';
                        }
                        var arr = href.split('::');
                        if (arr.length == 2) {
                            var appid = arr[0] === '' ? getCurrentRuntimeAppID() : YangRAM.API.APP.checkAppid(arr[0]);
                            var href = arr[1];
                        } else {
                            var appid = getAppId(this);
                        }
                        //console.log(arr, appid);
                        appid && Browser.handlers[protocol] && Browser.handlers[protocol](href, appid, this);
                    } else {
                        var appid = getAppId(this);
                        YangRAM.API.APP.launch(appid, href);
                    }
                }
            }
        }
    };

    _.extend(Browser, true, {
        //name: Runtime.locales.HIGHBAR.TAM.APPNAME,
        pageviews: {},
        build() {
            this.tools = YangRAM.create('browsertools', System.HiBar.TitleAndMenu.document, {
                state: 'off',
                html: '<closebrowser></closebrowser><browseprev></browseprev><refresh></refresh><browsenext></browsenext>'
            });
            return this.resize().listenEvents(events);
        },
        resize() {
            YangRAM.setStyle(this.document, {
                width: Workspace.appWidth,
                height: System.Height,
                top: 0,
                left: Workspace.AppLeft
            });
            var appid = getCurrentRuntimeAppID();
            if (this.pageviews[appid]) {
                this.pageviews[appid].resize();
            }
            return this;
        },
        launch() {
            if (this.state == false) {
                this.state = true;
                this.attr('state', 'off').attr('state', 'on').resize();
                System.HiBar.TitleAndMenu.launch().setViewStatus('browsing');
            }
            return this;
        },
        sleep(appid) {
            if (this.state) {
                this.state = false;
                this.attr('state', 'off');
                if ((appid !== undefined) && this.pageviews[appid]) {
                    this.pageviews[appid].sleep();
                }
                System.HiBar.TitleAndMenu.launch().setViewStatus('normal');
            }
            return this;
        },
        check() {
            var appid = getCurrentRuntimeAppID();
            if (this.pageviews[appid] && this.pageviews[appid].state) {
                this.sleep();
                setTimeout(() => this.launch(), 600)
            } else {
                this.sleep(appid);
            }
        },
        browse(url) {
            var appid = getCurrentRuntimeAppID();
            if (this.pageviews[appid] == undefined) {
                this.$('[running]').removeAttr('running');
                this.pageviews[appid] = new Pageview(appid);
            }
            this.pageviews[appid].loadURL(url, true);
            this.launch();
            return this;
        },
        handlers: {
            LAUNCH(href, appid) {
                YangRAM.API.APP.launch(appid, href);
            },
            TRIGGER(trigger, appid, elem) {
                var arg = YangRAM.attr(elem, 'args');
                var args = arg ? arg.split(/,\s*/) : [];
                YangRAM.attr(elem, 'readonly') == '' || (launchedApps[appid] && launchedApps[appid].API && launchedApps[appid].API.BROWSER_TRIGGERS && _.util.bool.isFn(launchedApps[appid].API.BROWSER_TRIGGERS[trigger]) && launchedApps[appid].API.BROWSER_TRIGGERS[trigger].apply(elem, args));
            },
            HTTP(href) {
                Browser.browse('http://' + href);
            },
            HTTPS(href) {
                Browser.browse('https://' + href);
            },
            BROWSE(href) {
                Browser.browse('//' + href);
            }
        },
        listenEvents(events) {
            _.each(events, (selector, handlers) => {
                _.each(handlers, (eventType, handler) => {
                    YangRAM.bindListener(selector, eventType, handler);
                });
            });
            return this;
        }
    }).build();
});