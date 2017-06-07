static menuOnHigabar = {
        appname: __LANG__.APPNAME,
        menus: {
            'Open': [{
                    title: __('MENUS')("Default Page"),
                    state: 'on',
                    handler: 'OpenDefault'
                },
                {
                    title: __('MENUS')("List Of Preset"),
                    state: 'on',
                    handler: 'OpenList'
                },
            ],
            'Operate': [{
                    title: 'New',
                    state: 'on',
                    handler: 'NewContent'
                },
                {
                    title: 'Save',
                    state: 'on',
                    handler: 'SaveContent'
                },
                {
                    title: 'Delete',
                    state: 'on',
                    handler: 'DeleteConten'
                },
                {
                    title: __('MENUS')("Preview"),
                    state: 'on',
                    handler: 'PreviewContent'
                }
            ],
            'Pageview': [{
                    title: 'Sleep',
                    state: 'on',
                    handler: 'Sleep'
                },
                {
                    title: 'Center',
                    state: 'on',
                    handler: 'Center'
                },
                {
                    title: 'Cover',
                    state: 'on',
                    handler: 'Cover'
                },
                {
                    title: 'Close',
                    state: 'on',
                    handler: 'Close'
                }
            ],
            'Help': [{
                    title: __('MENUS')("Dataroom Official Website"),
                    state: 'on',
                    handler: 'OfficialWebsite'
                },
                {
                    title: __('MENUS')("Publisher Forum"),
                    state: 'on',
                    handler: 'GotoForum'
                },
                {
                    title: __('MENUS')("About YangRAM Dataroom"),
                    state: 'on',
                    handler: 'GotoAbout'
                }
            ]
        }
	};