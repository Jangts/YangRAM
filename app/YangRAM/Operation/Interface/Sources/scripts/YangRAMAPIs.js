System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        launchedApps = Runtime.storage.launchedApplications,
        _ = System.Pandora;

    var Clipboard = new _.data.Clipboard(System.HiddenArea.create('textarea', {
        style: {
            position: 'fixed',
            top: -2000,
            left: -2000
        }
    }));

    return {
        DOM: _.dom,
        SYS: {
            width: () => System.Width,
            Height: () => System.Height
        },
        TXT: {
            copy(text, appid) {
                Clipboard.setText(text).copy();
                System.Notifier.notice({
                    appid: appid || Runtime.currentRunningAppID,
                    title: Runtime.locales.COMMON.COPY[0],
                    content: Runtime.locales.COMMON.COPY[1]
                });
            },
            dictReader(dict, word, args) {
                switch (typeof dict[word]) {
                    case 'object':
                        return (s, a) => {
                            return YangRAM.API.TXT.dictReader(dict[word], s, a)
                        };
                    case 'function':
                        if (args && args.length) {
                            return dict[word].apply(dict, args);
                        }
                        //return dict[word];
                    case 'string':
                        return dict[word];
                    default:
                        return word;
                }
            },
            local(word, args) {
                return YangRAM.API.TXT.dictReader(Runtime.locales, word, args);
            }
        },
        MSG: {
            alert: System.Notifier.alert,
            popup: System.Notifier.popup,
            notice: System.Notifier.notice,
            remain: System.Notifier.remain
        },
        CHK: {
            recycle() {
                launchedApps[2] && Runtime.application(2).refresh();
            }
        },
        APP: {
            width: () => System.Workspace.appWidth,
            fsHeight: () => System.Workspace.appFullScreenHeightMin,
            checkAppid(appid, withouti4plaza) {
                if (withouti4plaza) {
                    var arr = ['SETTINGS', 'EXPLORER', 'TRASHCAN'];
                } else {
                    var arr = ['SETTINGS', 'I4PLAZA', 'EXPLORER', 'TRASHCAN'];
                }
                if (_.util.arr.has(arr, appid) !== false) {
                    return appid;
                }
                return parseInt(appid) || false;
            },
            getAppidByElement(elem, withouti4plaza) {
                var appid = YangRAM.attr(elem, 'appid');
                if (_.util.arr.has(['SETTINGS', 'EXPLORER', 'TRASHCAN'], appid) === false) {
                    appid = parseInt(appid) || (withouti4plaza ? '' : 'I4PLAZA');
                }
                return appid;
            },
            launch(appid, href) {
                System.Workspace.isWorking || System.Workspace.toWork();
                if (appid == 'MEMOWALL') {
                    System.Workspace.Launcher.on().Memowall.launch();
                } else if (appid == 'SYSTEM-MODULES') {
                    return YangRAM;
                } else {
                    System.Workspace.Launcher.Memowall.sleep();
                    Runtime.application(appid, true).launch(href);
                }
                return YangRAM;
            },
            sleep(appid, completely) {
                if (appid == 'SYSTEM-MODULES' && YangRAM.$('application', Runtime.AppWindows).length > 0) {
                    System.Notifier.sleep();
                    System.ProcessBus.sleep();
                    System.Smartian.sleep();
                    System.Kalendar.sleep();
                    if (completely) {
                        System.Workspace.Launcher.sleep();
                        System.Workspace.Browser.sleep();
                        System.HiBar.TitleAndMenu.sleep();
                    } else {
                        System.Workspace.Launcher.Memowall.sleep();
                    }
                    return YangRAM;
                }
                if (appid == 'MEMOWALL' && YangRAM.$('application', Runtime.AppWindows).length > 0) {
                    System.Workspace.Launcher.Memowall.sleep();
                    return YangRAM;
                }
                if (appid == Runtime.currentRunningAppID) {
                    appid = YangRAM.API.APP.checkAppid(appid, true);
                    if (appid) {
                        var app = launchedApps[parseInt(appid)] || launchedApps[appid];
                        app.attr('layer-index', --System.Workspace.appLayerIndexesMin).layerIndex = System.Workspace.appLayerIndexesMin;
                        System.Workspace.appLayerIndexesMax--;
                        for (var i = System.Workspace.appLayerIndexesMax; i >= System.Workspace.appLayerIndexesMin && i != app.layerIndex; i--) {
                            var elem = YangRAM.$('[layer-index="' + i + '"]')[0];
                            if (elem) {
                                var nextrunappid = YangRAM.API.APP.getAppidByElement(elem);
                                Runtime.resortApps(nextrunappid, true, true);
                                return YangRAM;
                            }
                        };
                    };
                }
                return YangRAM;
            },
            close(appid, force, fast) {
                if (appid === 'I4PLAZA') {
                    return YangRAM;
                }
                if (appid === 'PROCESSBUS') {
                    System.ProcessBus.sleep();
                    return YangRAM;
                }
                if (_.util.arr.has(['SETTINGS', 'EXPLORER', 'TRASHCAN'], appid) !== false) {
                    new System.Notifier.Message({
                        appid: appid,
                        title: '操作替换提醒',
                        content: '您试图关闭的应用是一个基础服务应用，除非其发生致命错误，否则只会将其隐藏。'
                    }).notice();
                    if (force) {
                        var app = launchedApps[appid];
                        //console.log(app);
                        app.launch('default');
                    }
                    return YangRAM.API.APP.sleep(appid);
                }
                if (parseInt(appid) && launchedApps[appid]) {
                    appid = parseInt(appid);
                    if (force) {
                        var app = launchedApps[appid],
                            duration;
                        if (fast) {
                            for (var i = app.layerIndex - 1; i >= System.Workspace.appLayerIndexesMin; i--) {
                                var elem = YangRAM.$('[layer-index="' + i + '"]')[0];
                                if (elem) {
                                    Runtime.currentRunningAppID = YangRAM.API.APP.getAppidByElement(elem);
                                    break;
                                } else {
                                    Runtime.currentRunningAppID = 0;
                                }
                            };
                            duration = 0;
                            if (app.layerIndex == System.Workspace.appLayerIndexesMax) {
                                --System.Workspace.appLayerIndexesMax;
                            }
                        } else {
                            duration = 600;
                            this.sleep(appid, fast);
                        }
                        if (app.layerIndex == System.Workspace.appLayerIndexesMin) {
                            ++System.Workspace.appLayerIndexesMin;
                        }
                        app.release()._dest();
                        YangRAM.$('[appid=' + appid + ']').attr('state', 'off').removeAttr('running');;
                        setTimeout(() => {
                            _.dom.events.remove(app.document);
                            app.document && app.document.parentNode && app.document.parentNode.parentNode && app.document.parentNode.parentNode.removeChild(app.document.parentNode);
                            app.__os && app.__os.parentNode && app.__os.parentNode.removeChild(app.__os);
                            app.__stylesheetslink && app.__stylesheetslink.parentNode && app.__stylesheetslink.parentNode.removeChild(app.__stylesheetslink);
                        }, duration);
                        System.HiBar.TitleAndMenu.unload(appid);
                        System.Workspace.OIMLElement.destroy(appid);
                        for (var i = 0; i < app.contextmenus.length; i++) {
                            System.HiBar.Contexts.unload(app.contextmenus[i]);
                        }
                        launchedApps[appid] = undefined;
                    } else {
                        if (Runtime.storage.activedApplications[appid]) {
                            new System.Notifier.Message({
                                title: Runtime.locales.COMMON.CLOSE.title_E,
                                content: Runtime.locales.COMMON.CLOSE.content_E(launchedApps[appid].name),
                                resolve: Runtime.locales.COMMON.CLOSE.resolve,
                                reject: Runtime.locales.COMMON.CLOSE.reject,
                                cancel: Runtime.locales.COMMON.CLOSE.cancel,
                                done: () => {
                                    this.close(appid, true);
                                },
                                fail: () => {
                                    this.launch(appid);
                                }
                            }).alert();
                        } else {
                            new System.Notifier.Message({
                                title: Runtime.locales.COMMON.CLOSE.title_N,
                                content: Runtime.locales.COMMON.CLOSE.content_N,
                                done: () => {
                                    this.close(appid, true);
                                }
                            }).alert();
                        }
                    };
                }
                return YangRAM;
            }
        }
    };
}, 'YangRAM');