static menuOnHigabar = {
		appname: __LANG__.APPNAME,
        menus: {
            'Common': [{
                    title: __('MENUS')("Default Page"),
                    state: 'on',
                    handler: 'OpenDefault'
                },
                {
                    title: __('MENUS')("Refresh"),
                    state: 'on',
                    handler: 'ReLoad'
                }
            ],
            'View': [{
                    title: 'Sleep',
                    state: 'on',
                    handler: 'Sleep'
                },
                {
                    title: 'Center',
                    state: 'off',
                    handler: 'Center'
                },
                {
                    title: 'Cover',
                    state: 'off',
                    handler: 'Cover'
                },
                {
                    title: 'Close',
                    state: 'on',
                    handler: 'Close'
                }
            ],
            'Help': [{
                    title: YangRAM.API.TXT.local('I4PLAZA')('MENUS')("YangRAM Official Website"),
                    state: 'on',
                    handler: 'GotoYangRAM'
                },
                {
                    title: YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Forum"),
                    state: 'on',
                    handler: 'GotoForum'
                },
                {
                    title: YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Developer Center"),
                    state: 'on',
                    handler: 'GotoDevCnt'
                },
                {
                    title: YangRAM.API.TXT.local('I4PLAZA')('MENUS')("About YangRAM"),
                    state: 'on',
                    handler: 'GotoAbout'
                }
            ],
        }
	},
    menuOnFolder = [
		[
			{
                title: __('MENUS')("Open Folder"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.OpenFolder
            },
            {
                title: __('MENUS')("Move To"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.MoveTo
            },
            {
                title: __('MENUS')("Rename"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Rename
            },
            {
                title: __('MENUS')("Delete Folder"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Delete
            }
        ]
	],
    menuOnSPC = [
		[{
            title: __('MENUS')("Delete Content"),
            state: 'on',
            handler: CTX_MENU_HANDLERS.Delete
        }]
	],
    menuOnDocument = [
		[{
                title: __('MENUS')("Move To"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.MoveTo
            },
            {
                title: __('MENUS')("Copy URL"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyURL
            },
            {
                title: __('MENUS')("Replace"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Replace
            },
            {
                title: __('MENUS')("Rename"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Rename
            },
            {
                title: __('MENUS')("Delete Documents"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Delete
            }
        ]
	],
    menuOnPicture = [
		[{
                title: __('MENUS')("Preview"),
                state: 'on',
                handler: PICS
            },
            {
                title: __('MENUS')("Move To"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.MoveTo
            },
            {
                title: __('MENUS')("Copy URL"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyURL
            },
            {
                title: __('MENUS')("Copy Code"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyCode
            },
            {
                title: __('MENUS')("Replace"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Replace
            },
            {
                title: __('MENUS')("Rename"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Rename
            },
            {
                title: __('MENUS')("Delete Documents"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Delete
            }
        ]
	],
    menuOnText = [
		[{
                title: __('MENUS')("Preview"),
                state: 'on',
                handler: TXTS
            },
            {
                title: __('MENUS')("Move To"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.MoveTo
            },
            {
                title: __('MENUS')("Copy URL"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyURL
            },
            {
                title: __('MENUS')("Replace"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Replace
            },
            {
                title: __('MENUS')("Rename"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Rename
            },
            {
                title: __('MENUS')("Delete Documents"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Delete
            }
        ]
	],
    menuOnAudio = [
		[{
                title: __('MENUS')("Play"),
                state: 'on',
                handler: WAVS
            },
            {
                title: __('MENUS')("Move To"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.MoveTo
            },
            {
                title: __('MENUS')("Copy URL"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyURL
            },
            {
                title: __('MENUS')("Copy HTML Code"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.CopyCode
            },
            {
                title: __('MENUS')("Replace"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Replace
            },
            {
                title: __('MENUS')("Rename"),
                state: 'on',
                handler() {}
            },
            {
                title: __('MENUS')("Delete Documents"),
                state: 'on',
                handler: CTX_MENU_HANDLERS.Delete
            }
        ]
	];
