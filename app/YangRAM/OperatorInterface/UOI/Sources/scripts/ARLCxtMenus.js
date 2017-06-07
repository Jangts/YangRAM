System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        _ = System.Pandora;

    return {
        dockappsMenu: [
            [{
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["launch"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Sleep"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Awake"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Close"],
                    state: 'on',
                    handler() {

                    }
                }
            ],
            [{
                title: Runtime.locales.LAUNCHER.ARL.MENUS["Uninstall"],
                state: 'on',
                handler() {

                }
            }]
        ],
        memowallMenu: [
            [{
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["On/Off"],
                    state: 'on',
                    handler() {
                        System.Workspace.Launcher.state ? System.Workspace.Launcher.Memowall.sleep() : System.Workspace.Launcher.Memowall.launch();
                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["New Group"],
                    state: 'on',
                    handler() {
                        System.Workspace.Launcher.NewGroup();
                    }
                }
            ]
        ],
        settingsMenu: [
            [{
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Basic/General"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Channel/Columns/Router"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Content Presets"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Applications/Themes"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Driver/Protocol"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Operators Manage"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Personal"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Agency/Languges"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Update/Safety"],
                    state: 'on',
                    handler() {}
                }
            ]
        ],
        trashcanMenu: [
            [{
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["New TrashCan Rlue"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Empty Recycling Items"],
                    state: 'on',
                    handler() {}
                }
            ]
        ],
        explorerMenu: [
            [{
                title: Runtime.locales.LAUNCHER.ARL.MENUS["Preset Contents"],
                state: 'on',
                handler() {
                    YangRAM.API.APP.launch(0, 'ctt');
                }
            }],
            [{
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Resources Library"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Pictures"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["documents"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Texts"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Compressed Files"],
                    state: 'on',
                    handler() {}
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Videos"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Musics"],
                    state: 'on',
                    handler() {

                    }
                },
                {
                    title: Runtime.locales.LAUNCHER.ARL.MENUS["Other Archives"],
                    state: 'on',
                    handler() {

                    }
                }
            ]
        ]
    };
}, 'ARLCxtMenus');