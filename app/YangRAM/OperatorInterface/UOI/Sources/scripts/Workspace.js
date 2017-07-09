System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Workspace = System.Workspace,
        _ = System.Pandora;

    var events = {
            click(event) {
                if (!Workspace.isWorking) {
                    var index = parseInt(YangRAM.attr(this, 'layer-index'));
                    var posi = parseInt(YangRAM.attr(this, 'list-posi'));
                    if (posi) {
                        Workspace.currentLayerIndex = index;
                        Workspace.resortInListMode();
                    } else {
                        var appid = YangRAM.API.APP.getAppidByElement(this);
                        YangRAM.attr(System.HiBar.WSMSwitcher, 'type', 'start');
                        //console.log(appid);
                        Workspace.toWork(appid);
                    }
                }
            },
            rclick(event) {
                if (!Workspace.isWorking) {
                    var index = parseInt(YangRAM.attr(this, 'layer-index'));
                    var posi = parseInt(YangRAM.attr(this, 'list-posi'));
                    if (posi) {
                        Workspace.currentLayerIndex = index;
                        Workspace.resortInListMode();
                    } else {
                        var appid = parseInt(YangRAM.attr(this, 'appid'));
                        //console.log(appid);
                        if (appid) {
                            YangRAM.API.APP.close(appid, true, true);
                            if (index == Workspace.appLayerIndexesMin) {
                                Workspace.currentLayerIndex++;
                            }
                            Workspace.appLayerIndexesMin++;
                            setTimeout(() => {
                                Workspace.resortApplications(false);
                            }, 500);
                        }
                    }
                }
            },
            mousewheel(event) {
                if (!Workspace.isWorking) {
                    if (event.wheelDelta == -120 && Workspace.currentLayerIndex < Workspace.appLayerIndexesMax) {
                        Workspace.currentLayerIndex++;
                        Workspace.resortInListMode();
                    }
                    if (event.wheelDelta == 120 && Workspace.currentLayerIndex > Workspace.appLayerIndexesMin + 1) {
                        Workspace.currentLayerIndex--;
                        Workspace.resortInListMode();
                    }
                }
            }
        },
        recoverAppStyle = (query) => {
            return query.each(function() {
                var aid = YangRAM.API.APP.getAppidByElement(this);
                var app = Runtime.application(aid);
                if (app.document) {
                    if (aid) {
                        YangRAM.setStyle(app.document, {
                            'width': System.Width,
                            'min-width': System.Width,
                            'max-height': 'none',
                            'top': 0
                        });
                    }
                    if (app.viewstatus == 1) {
                        app.setFullScreenView(true);
                    } else {
                        app.setCenteredView(true);
                    }
                    app.document.parentNode.scrollTop = Workspace.documentsTops[aid];
                    Workspace.documentsTops[aid] = null;
                }
            })
        };

    _.extend(Workspace, true, {
        viewstatus: 'workmode',
        isWorking: true,
        timer: null,
        launcherState: null,
        listingApplications: [],
        documentsTops: {},
        appLayerIndexesMax: 0,
        appLayerIndexesMin: 1,
        currentLayerIndex: 0,
        onafterresize() {
            this.Width = System.Width;
            this.Height = System.Height;
            YangRAM.setStyle(this.document, {
                width: this.Width,
                height: this.Height
            });
            YangRAM.setStyle(YangRAM.AppWorkspace, {
                width: this.Width,
                height: this.Height
            });
            if (Workspace.Launcher.state) {
                this.AppLeft = 50;
            } else {
                this.AppLeft = 0;
            }
            this.appWidth = System.Width - this.AppLeft;
            this.appFullScreenHeightMin = System.Height - 40;
            this.Browser.resize();
            var app = Runtime.application();
            if (this.isWorking) {
                YangRAM.$('yangram>workspace>windows>application>oiml').css({
                    'width': Workspace.appWidth,
                    'min-width': Workspace.appWidth,
                }).attr('type', Workspace.AppLeft ? 'right-status' : 'full-status');
                //app ? app.resize() : console.log(Workspace.Launcher.state, Runtime.currentRunningAppID, app, YangRAM.appWidth);
                app && app.resize();
                return this;
            } else {
                for (var n = 0; n < this.listingApplications.length; n++) {
                    app = Runtime.application(this.listingApplications[n]);
                    app.resize && app.resize();
                };
                return this.resortInListMode();
            }
        },
        build() {
            System.ProcessBus.build();
            return this.listenEvents();
        },
        toWork(appid) {
            if (this.viewstatus === 'listmode') {
                Workspace.timer && clearTimeout(Workspace.timer);
                Workspace.timer = setTimeout(() => {
                    Workspace.timer && clearTimeout(Workspace.timer);
                    Workspace.timer = setTimeout(() => {
                        query.attr('scroll', '');

                        YangRAM.attr(System.HiBar.WSMSwitcher, 'type', 'start');
                        Workspace.attr('viewstatus', 'workmode').resize();
                        if (this.launcherState) {
                            Workspace.Launcher.launch();
                        }
                        this.launcherState = null;
                    }, 700);
                    Workspace.attr('viewstatus', 'beforework').$('wallpapers').css({
                        'max-height': '100%',
                    });
                    recoverAppStyle(query);
                    System.HiBar.TitleAndMenu.on();
                }, 300);

                appid = YangRAM.API.APP.checkAppid(appid) || Runtime.currentRunningAppID;
                this.viewstatus = 'workmode';
                this.isWorking = true;
                this.currentLayerIndex = 0;
                this.listingApplications = [];
                Workspace.attr('viewstatus', 'afterlist');
                var query = YangRAM.$('windows>application').css('left', '0px');
                YangRAM.API.APP.launch(appid).$('[appid="' + appid + '"]').attr('runstatus', 'working').attr('running', '');
            }
            return this;
        },
        listing() {
            if (this.viewstatus === 'workmode') {
                Workspace.timer && clearTimeout(Workspace.timer);
                Workspace.timer = setTimeout(() => {
                    Workspace.timer && clearTimeout(Workspace.timer);
                    Workspace.timer = setTimeout(() => {
                        Workspace.attr('viewstatus', 'listmode').resortApplications(false).resize();
                    }, 400);
                    Workspace.attr('viewstatus', 'beforelist').$('wallpapers').css({
                        'max-height': System.Height - 40,
                    });
                }, 0);

                if (this.launcherState === null) {
                    this.launcherState = Workspace.Launcher.state;
                }
                YangRAM.API.APP
                    .sleep('SYSTEM-MODULES', true)
                    .attr(System.HiBar.WSMSwitcher, 'type', 'wsmswitcher')
                    .$('windows>application').removeAttr('scroll');

                this.attr('viewstatus', 'afterwork').viewstatus = 'listmode';
                this.isWorking = false;
            }
            return this;
        },
        resortApplications(isWorking) {
            if (isWorking) {
                this.WorkingApps = ['I4PLAZA'];
                for (var i = this.appLayerIndexesMax; i >= this.appLayerIndexesMin; i--) {
                    var $app = YangRAM.$('windows application[layer-index="' + i + '"]');
                    if ($app.length > 0) {
                        appid = YangRAM.API.APP.getAppidByElement($app[0], true);
                        if (appid && Runtime.application(appid)) {
                            this.WorkingApps.push(appid);
                        }
                    };
                }
                return this.resortInWorkMode();
            }
            var AppTops = {};
            this.listingApplications = ['I4PLAZA'];
            this.currentLayerIndex = Runtime.currentRunningAppID == 'I4PLAZA' ? 1 : this.currentLayerIndex;
            for (var i = this.appLayerIndexesMax; i >= this.appLayerIndexesMin; i--) {
                var $app = YangRAM.$('windows application[layer-index="' + i + '"]');
                if ($app.length > 0) {
                    appid = YangRAM.API.APP.getAppidByElement($app[0], true);
                    var doc = Runtime.application(appid).document;
                    if (appid && doc) {
                        this.documentsTops[appid] = _.util.bool.isNum(this.documentsTops[appid]) ? this.documentsTops[appid] : doc.parentNode.scrollTop;
                        this.listingApplications.push(appid);
                    }
                };
            }
            //console.log(this.listingApplications);
            return this.resortInListMode();
        },
        resortInListMode() {
            var apps = this.listingApplications;
            var newLayerIndex = 1;
            var index = this.currentLayerIndex;
            this.appLayerIndexesMax = newLayerIndex;
            for (var n = 0; n < apps.length; n++) {
                var appid = apps[n];
                var app = Runtime.application(appid);
                if (app.document) {
                    var posi = newLayerIndex - index;
                    app.setListModeView().attr('layer-index', newLayerIndex).attr('list-posi', posi).layerIndex = newLayerIndex;
                    YangRAM.setStyle(app.document.parentNode, {
                        left: posi * 550 + System.Width / 2,
                        zIndex: newLayerIndex
                    });
                    if (appid) {
                        YangRAM.setStyle(app.document, {
                            'max-height': System.Height - 40,
                        });
                        YangRAM.setStyle(app.document, {
                            'top': (System.Height - YangRAM.$(app.document).outerHeight()) / 2
                        });
                    }
                    newLayerIndex--;
                }
            }
            this.appLayerIndexesMin = newLayerIndex;
            return this;
        },
        resortInWorkMode() {
            var apps = this.WorkingApps;
            var newLayerIndex = 1;
            var appid;
            var app;
            this.appLayerIndexesMax = newLayerIndex;
            for (var i = 0; i < apps.length; i++) {
                appid = apps[i];
                app = Runtime.application(appid);
                //console.log(apps, appid, app);
                app.attr('layer-index', newLayerIndex).layerIndex = newLayerIndex;
                if (appid) {
                    let elem = app.Element,
                        index = newLayerIndex,
                        selects = YangRAM.$('application[appid="' + appid + '"][running], bgpic[appid="' + appid + '"][running]');
                    //console.log(selects);
                    if (selects[0]) {
                        var selectel = selects[0];
                        setTimeout(() => {
                            setTimeout(() => {
                                selects.attr('runstatus', 'sleeping');
                            }, 500);
                            selects.attr('runstatus', 'returning');
                        }, 0);
                        selects.attr('state', 'on').removeAttr('running').attr('runstatus', 'afterworking');
                    } else {
                        YangRAM.setStyle(elem, { zIndex: index });
                    }
                } else {
                    Runtime.currentRunningAppID = 'I4PLAZA';
                    YangRAM.setStyle(app.document, {
                        zIndex: newLayerIndex
                    }).$('[appid="' + appid + '"]').attr('state', 'on').attr('running', '');
                }
                newLayerIndex--;
            }
            this.appLayerIndexesMin = newLayerIndex + 1;
            return this;
        },
        listenEvents() {
            YangRAM.bindListener('workspace[viewstatus=listmode] windows application', 'click', events.click)
                .bindListener('workspace[viewstatus=listmode] windows application', 'rclick', events.rclick)
                .bindListener('workspace[viewstatus=listmode] windows', 'mousewheel', events.mousewheel);
            return this;
        }
    }).build();
});