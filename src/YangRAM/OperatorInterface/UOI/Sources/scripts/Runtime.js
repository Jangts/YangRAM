iBlock((pandora, global, undefined) => {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        operationscripts = Runtime.operationscripts;

    if (System.ModuleSeeds && System.ModuleSeeds.models && System.ModuleSeeds.models.Application) {

        var presetApps = {
                'APP-MEMOWALL': {
                    id: 'MEMOWALL',
                    name: Runtime.locales.LAUNCHER.MMW.APPNAME,
                    icon: 'grid',
                    menu: 'wall'
                },
                'APP-SETTINGS': {
                    id: 'SETTINGS',
                    name: Runtime.locales.SETTINGS.APPNAME,
                    icon: 'settings',
                    menu: 'settings'
                },
                'APP-I4PLAZA': {
                    id: 'I4PLAZA',
                    name: Runtime.locales.I4PLAZA.APPNAME,
                    icon: '',
                    Path: '',
                    menu: ''
                },
                'APP-EXPLORER': {
                    id: 'EXPLORER',
                    name: Runtime.locales.EXPLORER.APPNAME,
                    icon: 'compass',
                    menu: 'explorer'
                },
                'APP-TRASHCAN': {
                    id: 'TRASHCAN',
                    name: Runtime.locales.TRASHCAN.APPNAME,
                    icon: 'trash',
                    menu: 'trashcan'
                }
            },
            bindElementTimer = (elem, callback, duration) => {
                clearElementTimer(elem);
                elem.timer = setTimeout(() => {
                    clearElementTimer(elem);
                    callback();
                }, duration);
                return true;
            },
            clearElementTimer = (elem) => {
                elem.timer && clearTimeout(elem.timer);
                delete elem.timer;
                return true;
            },
            switchCurrentRunningApp = (appid, forceswitch) => {
                var $curr, curr_elem;

                /* 与当前运行应用相关的_.dom.Elements对象 */
                $curr = YangRAM.$('application[running]');

                /* 与当前运行应用主视图的HTML对象 */
                curr_elem = $curr[0];

                /* 如果存在运行的应用 */
                if (curr_elem) {
                    //console.log($curr);
                    bindElementTimer(curr_elem, () => {
                        bindElementTimer(curr_elem, () => {
                            $curr
                                .attr('runstatus', 'sleeping');
                        }, 500);
                        $curr
                            .attr('runstatus', 'beforesleeping');
                    }, 0);
                    $curr
                        .removeAttr('running')
                        .attr('runstatus', 'afterworking');

                }
                Runtime.currentRunningAppID = appid;
                return forceswitch === false ? false : true;
            };

        _.extend(Runtime, true, {
            timeStamp: new Date().getTime(),
            storage: {
                presetApplications: presetApps,
                installedApplications: {},
                launchedApplications: {},
                activedApplications: {}
            },
            resortApps(appid, switchcurrent, forceswitch) {
                var app = Runtime.storage.launchedApplications[appid];
                if (app) {
                    /* 如果请求指定的应用不是当前运行应用 */
                    if (Runtime.currentRunningAppID != appid) {
                        /* 判断是否需要将运行应用切换为请求的应用 */
                        if (switchcurrent) {
                            forceswitch = switchCurrentRunningApp(appid, forceswitch);
                        }
                    }

                    var currApp, $curr, curr_elem;
                    currApp = Runtime.storage.launchedApplications[Runtime.currentRunningAppID];
                    System.Workspace.Browser.check();
                    if (currApp.layerIndex < System.Workspace.appLayerIndexesMax) {
                        if (currApp.layerIndex == System.Workspace.appLayerIndexesMin) {
                            ++System.Workspace.appLayerIndexesMin;
                        }
                        ++System.Workspace.appLayerIndexesMax;
                    }

                    /* 重置当前运行应用的Index为最高值 */
                    currApp.attr('layer-index', System.Workspace.appLayerIndexesMax).layerIndex = System.Workspace.appLayerIndexesMax;
                    _.util.bool.isFn(currApp.resize) && currApp.resize();

                    /* 与当前运行应用相关的_.dom.Elements对象 */
                    $curr = YangRAM.setStyle(app.document.parentNode, {
                        zIndex: System.Workspace.appLayerIndexesMax
                    }).$('[appid="' + appid + '"]');

                    if (forceswitch) {
                        /* 与当前运行应用主视图的HTML对象 */
                        curr_elem = $curr[0];
                        if (curr_elem) {
                            bindElementTimer(curr_elem, () => {
                                bindElementTimer(curr_elem, () => {
                                    $curr.attr('runstatus', 'working');
                                }, 500)
                                $curr.attr('runstatus', 'beforeworking');
                            }, 100);

                            $curr.attr('state', 'on')
                                .attr('running', '')
                                .attr('runstatus', 'aftersleeping');
                        }
                    }
                } else {
                    Runtime.storage.launchedApplications.I4PLAZA.launch();
                }
                return YangRAM;
            },
            application(appid, create) {
                var _appid = appid;
                if (Runtime.storage.launchedApplications[appid]) {
                    return Runtime.storage.launchedApplications[appid];
                }
                if (appid == undefined && (_.util.bool.isNumeric(Runtime.currentRunningAppID) || _.util.arr.has(['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'], Runtime.currentRunningAppID) !== false)) {
                    return Runtime.storage.launchedApplications[Runtime.currentRunningAppID];
                }

                if (create) {
                    if (!_.util.arr.has(['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'], appid) !== false) {
                        appid = YangRAM.API.APP.checkAppid(appid);
                    }
                    if (appid) {
                        if (Runtime.storage.installedApplications["APP-" + appid] || Runtime.storage.presetApplications["APP-" + appid]) {
                            return new Application(appid);
                        }
                    };
                    return {
                        launch() {
                            alert('Error Application Identification [' + _appid + ']');
                        }
                    };
                }
                return {
                    resize() {}
                };
            },
            AppLoadOS(mainURL, href) {
                //console.log('foo');
                href = _.util.bool.isStr(href) ? href : 'default/';
                var Script = System.HiddenArea.create('js', { appid: this.appid });
                Script.src = mainURL;
                Script.addEventListener('load', () => {
                    this.__os = Script;
                    var duration = (this.appid == 'I4PLAZA') ? 0 : 1400;
                    if (typeof operationscripts[this.appid] === 'function') {
                        if (_.util.arr.has(['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'], this.appid) !== false) {
                            //console.log(operationscripts[this.appid]);
                            operationscripts[this.appid](this, System, YangRAM, _.util.imports, global, undefined);
                        } else {
                            operationscripts[this.appid](this, YangRAM, _.util.imports, global, undefined);
                        }
                        if (typeof this.main === 'function') {
                            setTimeout(() => {
                                this.href = href;
                                this.main(this.appid, href);
                                Runtime.resortApps(this.appid);
                                if (this.appid) {
                                    System.Notifier.notice({
                                        appid: this.appid,
                                        title: Runtime.locales.COMMON.LAUNCH_SUCCESS[0],
                                        content: Runtime.locales.COMMON.LAUNCH_SUCCESS[1](this.name)
                                    }, false)
                                };
                            }, duration);
                        } else {
                            if (Runtime.currentRunningAppID != 'I4PLAZA') {
                                Runtime.AppLoadError.call(this, 'MF_NOT_FOUND');
                            } else {
                                global.console.log(this);
                            }
                        }
                    } else {
                        if (Runtime.currentRunningAppID) {
                            Runtime.AppLoadError.call(this, 'AP_NOT_FOUND');
                        } else {
                            global.console.log(this);
                        }
                    }
                });
            },
            AppLoadError(type) {
                var duration = 500;
                alert(Runtime.locales.COMMON[type], () => {
                    YangRAM.API.APP.close(this.appid, true);
                });
            },
            checkActivities(order) {
                if (order === true) {
                    return _.util.obj.length(Runtime.storage.activedApplications);
                };
                if (order === false) {
                    Runtime.storage.activedApplications = {};
                    return this;
                };
                if (_.util.obj.length(Runtime.storage.activedApplications) > 0) {
                    for (var i in Runtime.storage.activedApplications) {
                        var result = Runtime.storage.launchedApplications[i].name;
                        break;
                    }
                    result += Runtime.locales.checkActivities[0];
                    result += Runtime.locales.checkActivities[1];
                    return result;
                }
                return false;
            },
            sleepAllComponents(force) {
                System.Smartian.sleep();
                System.Kalendar.sleep();
                System.Notifier.sleep();
                System.ProcessBus.sleep();
                if (force) {
                    System.Workspace.Launcher.Memowall.sleep().PassiveMode = true;
                } else {
                    System.Workspace.Launcher.sleepWall();
                }
                return this;
            }
        });

        var Application = System.ModuleSeeds.models.Application(_, declare, global, undefined),
            YangRAM = System.ModuleSeeds.main(Application, _, declare, global, undefined);

        System.ModuleSeeds.models.Application = undefined;
        System.ModuleSeeds.main = undefined;
    } else {
        location.reload();
    }
});