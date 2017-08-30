System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Bookmark = Launcher.Bookmark,
        _ = System.Pandora;

    var contextMenus = System.ModuleSeeds.apis.ARLCxtMenus(YangRAM, declare, global, undefined);
    System.ModuleSeeds.apis.ARLCxtMenus = undefined;
    System.ModuleSeeds.APIs = undefined;

    var AppnameCode = (name) => {
            if (name.charCodeAt(0) > 128) {
                return name.substr(0, 1);
            }
            var code = '';
            for (var i = 0; i < name.length; i++) {
                if (name.charCodeAt(i) >= 48 && name.charCodeAt(i) <= 57) {
                    code += name.substr(i, 1);
                } else if (name.charCodeAt(i) >= 65 && name.charCodeAt(i) <= 90) {
                    code += name.substr(i, 1);
                } else if (name.charCodeAt(i) >= 97 && name.charCodeAt(i) <= 122) {
                    code += name.substr(i, 1);
                }
                if (code.length >= 2) {
                    break;
                }
            }
            return code.replace(/^\w/, (s) => {
                return s.toUpperCase();
            });
        },
        getStrLeng = (str) => {
            var realLength = 0;
            var len = str.length;
            var charCode = -1;
            for (var i = 0; i < len; i++) {
                charCode = str.charCodeAt(i);
                if (charCode >= 0 && charCode <= 128) {
                    realLength += 1;
                } else {
                    realLength += 2;
                }
            }
            return realLength;
        },
        create = {
            Preset(appid, href) {
                var app = Runtime.storage.presetApplications["APP-" + appid];
                //console.log(appid, app);
                return YangRAM.create('application', false, {
                    appid: appid,
                    href: href,
                    menu: app.menu,
                    tips: parseInt(30 * Math.random()),
                    state: 'off',
                    innerHTML: '<icon data="' + app.icon + '"></icon><appname>' + app.name + '</appname>'
                });
            },
            Normal(app) {
                if (app.enAb && app.enAb.length > 0) {
                    var abb = app.enAb;
                } else {
                    var abb = AppnameCode(app.name);
                }
                return YangRAM.create('application', false, {
                    appid: app.id,
                    href: 'default',
                    menu: app.menu,
                    isnew: app.isnew,
                    tips: parseInt(5 * Math.random()),
                    state: 'off',
                    innerHTML: '<icon data="' + app.icon + '" word="' + abb + '"></icon><appname' + app.longtextTag + '>' + app.name + '</appname>'
                });
            }
        };

    var memowall = create.Preset('MEMOWALL', 'launch'),
        explorer = create.Preset('EXPLORER', 'default'),
        trashcan = create.Preset('TRASHCAN', 'default'),
        settings = create.Preset('SETTINGS', 'default');

    _.extend(ARL, true, {
        topApps: YangRAM.create('systemapps', false, {
            type: 'top'
        }),
        middleApps: YangRAM.create('noramlapps', false, {
            innerHTML: '<content></content><scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>'
        }),
        bottomApps: YangRAM.create('systemapps', false, {
            type: 'bottom'
        }),
        build(callback) {
            this.topApps.appendChild(explorer);
            this.topApps.appendChild(settings);
            this.bottomApps.appendChild(memowall);
            this.bottomApps.appendChild(trashcan);
            this.document.appendChild(YangRAM.create('mask'));
            this.document.appendChild(this.topApps);
            this.document.appendChild(this.middleApps);
            this.document.appendChild(this.bottomApps);
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(this.middleApps);
            this.scrollBAR.toTop().disabled = true;
            return this.on()
                .regContextMenus('dock', contextMenus.dockappsMenu)
                .regContextMenus('wall', contextMenus.memowallMenu)
                .regContextMenus('explorer', contextMenus.explorerMenu)
                .regContextMenus('trashcan', contextMenus.trashcanMenu)
                .regContextMenus('settings', contextMenus.settingsMenu)
                .getInstalledApps(callback);
        },
        getInstalledApps(callback) {
            YangRAM.json(YangRAM.RequestDIR + 'i/sources/apps/', (allApps) => {
                var Lister = this.middleApps.getElementsByTagName('content').item(0);
                var longtextTag;
                for (var i in allApps) {
                    if (getStrLeng(allApps[i]['name']) > 20) {
                        longtextTag = ' longtext';
                    } else {
                        longtextTag = '';
                    }
                    Lister.appendChild(create.Normal({
                        id: allApps[i]['id'],
                        menu: 'dock',
                        icon: allApps[i]['icon'],
                        name: allApps[i]['name'],
                        longtextTag: longtextTag,
                        enAb: allApps[i]['code'],
                        isnew: allApps[i]['isnew']
                    }));
                    installedApps['APP-' + allApps[i]['id']] = allApps[i];
                }
                _.util.bool.isFn(callback) && callback();
            });
            return this;
        },
        showAll() {
            return this.on();
        },
        showHot() {
            return this.off();
        },
        showTop() {
            var viewstatus = YangRAM.attr(this.middleApps, 'viewstatus');
            if (viewstatus != 'openedonly') {
                YangRAM.attr(this.middleApps, 'viewstatus', 'openedonly');
            } else {
                YangRAM.attr(this.middleApps, 'viewstatus', 'allspps');
            }
            return this;
        }
    });
});