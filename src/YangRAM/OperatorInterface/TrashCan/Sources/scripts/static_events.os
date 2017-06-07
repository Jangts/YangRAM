static events = {
	'left item[href]' : {
				'click' (){
					var href = YangRAM.attr(this, 'href');
					__thisapp__.open(href);
				}
			},
			'main item[href]' : {
				'click' (){
					var href = YangRAM.attr(this, 'href');
					__thisapp__.open(href);
				}
			},
			'section.list-table .sele el' : {
				'click' (){
					var elem = this.parentNode.parentNode;
					if(elem.className=='head'){
						if(__thisapp__.$('section.list-table .list.selected').length>0){
							__thisapp__.$('section.list-table .list.selected').removeClass('selected');
						}else{
							__thisapp__.$('section.list-table .list').addClass('selected');
						}
					}else{
						YangRAM.toggleClass(elem, 'selected');
					}
				}
			},
			'section.img-list .sele el' : {
				'click' (){
					YangRAM.toggleClass(this.parentNode.parentNode, 'selected');
				}
			}
    };
	
static menuOnHigabar = {
	appname: System.Runtime.locales.TRASHCAN.APPNAME,
		menus: {
			'Settings': [
				{
					title: __('MENUS')("Create New Rule"),
					state: 'on',
					handler: 'NewRlue'
				},
				{
					title: __('MENUS')("Save Rule"),
					state: 'on',
					handler: 'SaveRlue'
				}
			],
			'Recover': [
				{
					title: __('MENUS')("Recover Selected"),
					state: 'on',
					handler: 'EmptySelectedItems'
				},
				{
					title: __('MENUS')("Recover Item"),
					state: 'on',
					handler: 'RestoreCurItems'
				},
				{
					title: __('MENUS')("Recover All"),
					state: 'on',
					handler: 'RestoreAllItems'
				}
			],
			'Delete': [
				{
					title: __('MENUS')("Delete Selected"),
					state: 'on',
					handler: 'EmptySelectedItems'
				},
				{
					title: __('MENUS')("Empty Item"),
					state: 'on',
					handler: 'EmptyCurItems'
				},
				{
					title: __('MENUS')("Empty All"),
					state: 'on',
					handler: 'EmptyAllItems'
				},
			],
			'View': [
				{
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
				}
			],
			'Help' : [
				{
					title : YangRAM.API.TXT.local('I4PLAZA')('MENUS')("YangRAM Official Website"),
					status : 'on',
					handler : 'GotoYangRAM'
				},
				{
					title : YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Forum"),
					status : 'on',
					handler : 'GotoForum'
				},
				{
					title : YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Developer Center"),
					status : 'on',
					handler : 'GotoDevCnt'
				},
				{
					title : YangRAM.API.TXT.local('I4PLAZA')('MENUS')("About YangRAM"),
					status : 'on',
					handler : 'GotoAbout'
				}
			]
		}
	};