System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Memowall = Launcher.Memowall,
        Modifier = Launcher.Modifier,
        _ = System.Pandora;

    var Bookmarks = System.ModuleSeeds.models.BookmarkGroup(YangRAM, declare, global, undefined);
    System.ModuleSeeds.models.BookmarkGroup = undefined;

    var HeadBar = {
        appname: Runtime.locales.LAUNCHER.MMW.APPNAME,
        menus: {
            "Common": [{
                    title: Runtime.locales.LAUNCHER.MMW.MENUS["New Group"],
                    state: 'on',
                    handler: 'NewGroup'
                },
                {
                    title: Runtime.locales.LAUNCHER.MMW.MENUS["New Link"],
                    state: 'on',
                    handler: 'NewLink'
                },
                {
                    title: Runtime.locales.LAUNCHER.MMW.MENUS["Sortting"],
                    state: 'on',
                    handler: 'Sortting'
                }
            ],
            "Help": [{
                    title: Runtime.locales.I4PLAZA.MENUS["System Official Website"],
                    state: 'on',
                    handler: 'GotoSystem'
                },
                {
                    title: Runtime.locales.I4PLAZA.MENUS["Forum"],
                    state: 'on',
                    handler: 'GotoForum'
                },
                {
                    title: Runtime.locales.I4PLAZA.MENUS["Developer Center"],
                    state: 'on',
                    handler: 'GotoDevCnt'
                },
                {
                    title: Runtime.locales.I4PLAZA.MENUS["About System"],
                    state: 'on',
                    handler: 'GotoAbout'
                }
            ],
        }
    };

    var MainMenu = [
            [{
                title: Runtime.locales.LAUNCHER.MMW.MENUS["Refresh"],
                state: 'on',
                handler() {
                    Launcher.refresh();
                }
            }]
        ],
        LinkMenu = [
            [{
                    title: Runtime.locales.LAUNCHER.MMW.MENUS["New Group"],
                    state: 'off',
                    handler: YangRAM.donothing
                },
                {
                    title: Runtime.locales.LAUNCHER.MMW.MENUS["Delete Link"],
                    state: 'on',
                    handler() {
                        Launcher.deleteItem(YangRAM.Contexts.Target);
                    }
                }
            ]
        ];

    _.extend(Memowall, true, {
        appname: Runtime.locales.LAUNCHER.MMW.APPNAME,
        ScrollBAR: undefined,
        YangRAMRunningApp: 0,
        build() {
            this.document.innerHTML = '<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>';
            this.MarkArea = YangRAM.create('content', this.document);
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(this.document);
            this.attr('appid', 'MEMOWALL')
                .attr('menu', 'memowall-interface')
                .regContextMenus('memowall-interface', MainMenu)
                .regContextMenus('bookmark', LinkMenu)
                .regHeadBar(HeadBar);
            return this;
        },
        refresh(callback) {
            YangRAM.get({
                url: YangRAM.RequestDIR + 'uoi/wall/marks/',
                done: (txt) => {
                    _.each(JSON.parse(txt), (i, group) => new Bookmarks(group));
                    this.scrollBAR.resize();
                    _.util.bool.isFn(callback) && callback();
                }
            });
            return this;
        },
        deleteItem() {
            this.refresh();
        },
        launch() {
            if (Runtime.currentRunningAppID !== 'MEMOWALL') {
                this.YangRAMRunningApp = Runtime.currentRunningAppID;
                YangRAM.$(this.document).css('display', 'block');
                YangRAM.$('[running]').removeAttr('running');
                setTimeout(() => {
                    YangRAM.$('[appid=MEMOWALL]').attr('running', '');
                }, 200);
                YangRAM.API.APP.sleep('SYSTEM-MODULES');
                Runtime.currentRunningAppID = 'MEMOWALL';
                Launcher.setViewStatus('wallmode');
            }
            return this;
        },
        sleep() {
            if (Runtime.currentRunningAppID === 'MEMOWALL' && Launcher.viewstatus === 'wallmode') {
                YangRAM.$('[running]').removeAttr('running');
                YangRAM.$(this.document).css('display', 'none');
                Launcher.setViewStatus('dockmode');
                Runtime.resortApps(this.YangRAMRunningApp, true, true);
            }
            return this;
        },
    });
});