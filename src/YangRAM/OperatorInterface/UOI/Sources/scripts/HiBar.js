System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        HiBar = System.HiBar,
        TitleAndMenu = System.HiBar.TitleAndMenu,
        _ = System.Pandora;

    var highBarMenu = [
        [{
                title: Runtime.locales.HIGHBAR.MENUS["Smartian"],
                state: 'on',
                handler() {
                    System.Smartian.state ? System.Smartian.sleep() : System.Smartian.launch();
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Msgcenter"],
                state: 'on',
                handler() {
                    System.Notifier.state ? System.Notifier.sleep() : System.Notifier.launch();
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Kalendar"],
                state: 'on',
                handler() {
                    System.Kalendar.state ? System.Kalendar.sleep() : System.Kalendar.launch();
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Taskmgr"],
                state: 'on',
                handler() {
                    System.ProcessBus.state ? System.ProcessBus.sleep() : System.ProcessBus.launch();
                }
            }
        ],
        [{
                title: Runtime.locales.HIGHBAR.MENUS["Center"],
                state: 'on',
                handler() {
                    Runtime.application(Runtime.currentRunningAppID).setCenteredView(true);
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Cover"],
                state: 'on',
                handler() {
                    Runtime.application(Runtime.currentRunningAppID).setFullScreenView(true);
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Sleep"],
                state: 'on',
                handler() {
                    YangRAM.API.APP.sleep(Runtime.currentRunningAppID);
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Close"],
                state: 'on',
                handler() {
                    YangRAM.API.APP.close(Runtime.currentRunningAppID);
                }
            }
        ]
    ];

    var startMenu = [
        [{
            title: Runtime.locales.HIGHBAR.MENUS["Frontpage"],
            state: 'on',
            handler() {
                global.open('/');
            }
        }],
        [{
                title: Runtime.locales.HIGHBAR.MENUS["Apps"],
                state: 'on',
                handler() {
                    System.Workspace.Launcher.state ? System.Workspace.Launcher.sleep() : System.Workspace.Launcher.launch();
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["LogOff"],
                state: 'on',
                handler() {
                    System.Logger.logoff();
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Lockscreen"],
                state: 'on',
                handler() {
                    System.Locker.launch();
                }
            }
        ],
        [{
                title: Runtime.locales.HIGHBAR.MENUS["Launcher"],
                state: 'on',
                handler() {
                    System.Workspace.Launcher.state ? YangRAM.API.APP.sleep('LAUNCHER') : YangRAM.API.APP.launch('LAUNCHER', 'wall');
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["Registry"],
                state: 'on',
                handler() {
                    YangRAM.API.APP.launch(52);
                }
            },
            {
                title: Runtime.locales.HIGHBAR.MENUS["t$"],
                state: 'on',
                handler() {
                    YangRAM.API.APP.launch(47);
                }
            }

        ]
    ];

    var events = {
            'start': {
                'click' (event) {
                    System.Workspace.isWorking && (System.Workspace.Launcher.state ? System.Workspace.Launcher.sleep() : System.Workspace.Launcher.launch());
                }
            },
            'myangram list': {
                'click' (event) {
                    System.Workspace.isWorking && HiBar.handlers.Account[YangRAM.attr(this, 'name')] && HiBar.handlers.Account[YangRAM.attr(this, 'name')]();
                }
            },
            'msger': {
                'click' (event) {
                    System.Notifier.state ? System.Notifier.sleep() : System.Notifier.launch();
                }
            },
            'appstore': {
                'click' (event) {
                    System.Workspace.toWork();
                    YangRAM.API.APP.launch(3, 'appstore');
                }
            },
            'wsmswitcher': {
                'click' (event) {
                    System.Workspace.isWorking ? System.Workspace.listing() : System.Workspace.toWork();
                }
            },
            'timer': {
                'click' (event) {
                    System.Workspace.isWorking && (System.Kalendar.state ? System.Kalendar.sleep() : System.Kalendar.launch());
                }
            },
            'searcher': {
                'click' (event) {
                    System.Workspace.isWorking && (System.Smartian.state ? System.Smartian.sleep() : System.Smartian.launch());
                }
            }
        },
        callback = (app, href) => {
            if (!app.API.HIGHBAR_HANDLERS) {
                app.API.HIGHBAR_HANDLERS = {};
            };
            if (!_.util.bool.isFn(app.API.HIGHBAR_HANDLERS[href])) {
                app.API.HIGHBAR_HANDLERS[href] = () => {
                    console.log(href);
                }
            }
            app.API.HIGHBAR_HANDLERS[href].call(app);
        },
        tamEvents = {
            'subbars appname': {
                'dblclick' (event) {
                    YangRAM.API.APP.launch('I4PLAZA');
                }
            },
            'subbars menu': {
                'mousedown' (event) {
                    var elem = event.target;
                    if (elem.tagName == 'MENU') {
                        if (YangRAM.attr(TitleAndMenu.document, 'actived') == 'actived') {
                            YangRAM.attr(TitleAndMenu.document, 'actived', 'false');
                        } else {
                            YangRAM.attr(TitleAndMenu.document, 'actived', 'actived');
                        }
                    }
                }
            },
            'subbars item': {
                'mousedown' (event) {
                    if (YangRAM.attr(this, 'state') == 'on') {
                        callback(Runtime.application(), YangRAM.attr(this, 'href'));
                    }
                }
            }
        };

    _.extend(HiBar, true, {
        name: Runtime.locales.HIGHBAR.APPNAME,
        handlers: {
            Account: {
                'acc-chk' (event) {
                    YangRAM.API.APP.launch(3, 'accout');
                },
                'msg-trn' (event) {
                    System.Workspace.toWork();
                    YangRAM.API.APP.launch(7, 'unread');
                },
                'acc-chk' (event) {
                    System.Workspace.toWork();
                    YangRAM.API.APP.launch(3, 'modinfo');
                },
                'scr-lok' (event) {
                    System.Locker.launch();
                },
                'log-off' (event) {
                    System.Logger.logoff();
                }
            }
        },
        build() {
            var components = [this.Start, this.Account, this.Clock, this.WSMSwitcher, this.Searcher];
            for (var i in components) {
                YangRAM.attr(components[i], 'type', 'start');
            }
            this.Clock.innerHTML = this.Timer.format('MM/dd  hh:mm:ss');
            this.Timer.loop(function() {
                HiBar.Clock.innerHTML = this.format('MM/dd  hh:mm:ss');
            }, 1000, true);
            YangRAM.attr(this.Start, 'menu', 'start');
            System.Kalendar.build();
            return this.attr('menu', 'hibar')
                .regContextMenus('hibar', highBarMenu)
                .regContextMenus('start', startMenu)
                .on().resize();
        },
        launch() {
            var components = [this.Start, this.Subbars, this.Account, this.Messager, this.WSMSwitcher, this.Searcher];
            for (var i in components) {
                YangRAM.attr(components[i], 'state', 'on');
            }
            return this;
        },
        sleep() {
            var components = [this.Start, this.Subbars, this.Account, this.Messager, this.WSMSwitcher, this.Searcher];
            for (var i in components) {
                YangRAM.attr(components[i], 'state', 'off');
            }
            return this;
        },
        onafterresize() {
            YangRAM.setStyle(this.document, { width: System.Width });
            System.Smartian.resize();
            System.Kalendar.resize();
        },
        listenEvents() {
            TitleAndMenu.listenEvents(tamEvents);
            //System.HiBar.Contexts.listenEvents();
            if (YangRAM.DebugMode == '0') {
                System.HiBar.Contexts.listenEvents();
                global.console.log = () => {
                    global.console.info('当前处于非调试状态！');
                }
            }
            System.Kalendar.listenEvents();
            System.Smartian.listenEvents();
            return this.__proto__.listenEvents.call(this, events);
        }
    });
});