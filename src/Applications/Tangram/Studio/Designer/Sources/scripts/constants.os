const $ = YangRAM.$,
    Name = __LANG__.APPNAME,
    TAMBAR = {
	appname: __LANG__.APPNAME,
		menus: {
			'Open': [{
				title: __LANG__.TOPMENUS['Default Page'],
				state: 'on',
				handler: 'OpenDefault'
			},
			{
				title: __LANG__.TOPMENUS['List Of Preset'],
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
				title: 'Remove',
				state: 'on',
				handler: 'RemoveConten'
			},
			{
				title: __LANG__.TOPMENUS['Preview'],
				state: 'on',
				handler: 'PreviewContent'
			}],
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
			}],
			'Help': [{
				title: 'Dataroom Official Website',
				state: 'on',
				handler: 'OfficialWebsite'
			},
			{
				title: 'Publisher Forum',
				state: 'on',
				handler: 'GotoForum'
			},
			{
				title: 'About YangRAM Dataroom',
				state: 'on',
				handler: 'GotoAbout'
			}]
		}
	};