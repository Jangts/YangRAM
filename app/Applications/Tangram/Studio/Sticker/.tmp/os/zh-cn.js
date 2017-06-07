RegApplication(1005, (__thisapp__, YangRAM, Using, Global, undefined) => {
'use strict';
const __LANG__ = {
    "CODE": "zh-cn",

    "APPNAME": "轻内容管理者",

    "TOPMENUS": {
        "Default Page": "首页",
        "List Of Preset": "返回预设列表",
        "Preview": "预览"
    },

    "WORDS": {
        "Remove Item?": "移除内容",
        "Are you sure to remove this item?": "确定要移除该内容？",
        "Reference Error": "错误的请求",
        "I'm Sorry!": "抱歉！",
        "Operation not supported!": "不支持此操作！",
        "Save Success": "入库成功",
        "A Content Has Been Saved!": "入库了一个内容",
        "Published": "已发布",
        "A Content Has Been Published!": "发布了一个内容！",
        "Remove Success": "删除成功",
        "A Content Has Been Removed!": "删除了一个内容！",
        "Specifies No Existing Item": "指定的项目不存在",
        "Confirm Close?": "确认关闭？",
        "You are trying to close a tab, are you sure to do this?": "将关闭一个选项卡，确定要这么做吗？",
        "Cut To": "转到",
        "Exceed Maximum Size Allowed Upload": "超出最大允许大小",
        "Unsupported File Format": "不受支持的文件格式",
        "Are you sure to go to the list page before save your editing?": "确定不保存正在编辑的内容，直接转到列表页？",
        "Are you sure to open a new item before save the editing item?": "确定不保存当前编辑的内容，直接打开其他内容？"
    }
};
const __ = (word) => {
	return YangRAM.API.TXT.dictReader(__LANG__, word);
};
const __APPDIR__ = '/Applications/Tangram/Studio/Sticker/';
const $ = YangRAM.$;
const  Name = __LANG__.APPNAME;
const  TAMBAR = {
	appname:__LANG__.APPNAME,
		menus:{
			'Open':[{
				title:__LANG__.TOPMENUS['Default Page'],
				state:'on',
				handler:'OpenDefault'
			},
			{
				title:__LANG__.TOPMENUS['List Of Preset'],
				state:'on',
				handler:'OpenList'
			},
			],
			'Operate':[{
				title:'New',
				state:'on',
				handler:'NewContent'
			},
			{
				title:'Save',
				state:'on',
				handler:'SaveContent'
			},
			{
				title:'Remove',
				state:'on',
				handler:'RemoveConten'
			},
			{
				title:__LANG__.TOPMENUS['Preview'],
				state:'on',
				handler:'PreviewContent'
			}],
			'Pageview':[{
				title:'Sleep',
				state:'on',
				handler:'Sleep'
			},
			{
				title:'Center',
				state:'on',
				handler:'Center'
			},
			{
				title:'Cover',
				state:'on',
				handler:'Cover'
			},
			{
				title:'Close',
				state:'on',
				handler:'Close'
			}],
			'Help':[{
				title:'Dataroom Official Website',
				state:'on',
				handler:'OfficialWebsite'
			},
			{
				title:'Publisher Forum',
				state:'on',
				handler:'GotoForum'
			},
			{
				title:'About YangRAM Dataroom',
				state:'on',
				handler:'GotoAbout'
			}]
		}
	};
const checkForm = function(data, preset){
		var elem=__thisapp__.$('form-vision form');
		var form=new YangRAM.API.form.Data(elem[0]);
		var fields=form.checkValue().data;
		for(var i in fields){
			data.append(i, fields[i][0]);
		}
		return data;
	};
const self = {
	controls : {
		'label-list' (){
			__thisapp__.open('list/');
		},
		'label-new' (){
			__thisapp__.open('form/emc/new/');
		},
		'label-save' (){
			var id=__thisapp__.$('form-vision').attr('x-cid'),
                sort=__thisapp__.$('form-vision').attr('x-sort'),
                page=__thisapp__.$('form-vision').attr('x-page'),
                type=__thisapp__.$('form-vision').attr('x-type'),
                group=__thisapp__.$('form-vision').attr('x-group');
			self.submit(id, 'save', sort, page, type, group);
		},
		'label-del' (){
			var id=__thisapp__.$('form-vision').attr('x-cid'),
                sort=__thisapp__.$('form-vision').attr('x-sort'),
                page=__thisapp__.$('form-vision').attr('x-page'),
                type=__thisapp__.$('form-vision').attr('x-type'),
                group=__thisapp__.$('form-vision').attr('x-group');
			if(parseInt(id)){
				YangRAM.API.MSG.popup({
					title:__LANG__.WORDS['Remove Item?'],
					content:__LANG__.WORDS['Are you sure to remove this item?'],
					confirm:YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('confirm'),
					cancel:YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('cancel'),
					done() {
						return self.submit(id, 'remove', sort, page, type, group);
					}
				});
			}else{
				YangRAM.API.MSG.notice({
					appid:__thisapp__.appid,
					title:__LANG__.WORDS['I\'m Sorry!'],
					content:__LANG__.WORDS['Operation not supported!'],
				});
			}
		}
	},
	submit(id, operate, sort, page, type, group){
		var url, title, content;
		var data=new FormData();
		data.append('id', id);
		switch(operate){
			case 'save':url=__thisapp__.__dirs.setter + 'submit/save/';
			title='Save Success';
			content='A Content Has Been Saved!';
			data=checkForm(data);
			break;
			case 'remove':url=__thisapp__.__dirs.setter + 'submit/remove/';
			title='Remove Success';
			content='A Content Has Been Removed!';
			break;
		}
		YangRAM.set({
			url:url,
			data:data,
			done(txt) {
				if(parseInt(txt)){
					YangRAM.API.MSG.notice({
						appId:__thisapp__.appid,
						title:title,
						content:content
					});
					if(group==undefined){
						__thisapp__.open('list/?sort='+sort+'&type='+type+'&page='+page, false, true);
					}else{
						//console.log('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page);
						__thisapp__.open('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page, false, true);
					}
					YangRAM.API.CHK.recycle();
				}else if (txt=='<CAN_NOT_FIND>') {
					alert('Specifies No Existing Item');
				}else{
					console.log(txt);
					alert('Unkonw Error');
				}
			},
			fail(txt) {
				console.log(txt);
				alert('Network Error');
			}
		});
	},
	events : {
	'ico.stk-icon':{
				'click' (){
					__thisapp__.open('default');
				}
			},
			'tools.stk-ctrl item':{
				'click' (){
					if(!YangRAM.API.DOM.hasClass(this, 'unavailable')){
						var type=YangRAM.attr(this, 'x-usefor');
						typeof self.controls[type]=='function' && self.controls[type]();
					};
				}
			}
    }};
const pm_5919743f7ba11 = {
	};
const pm_5919743f7ba15 = {
	API : {
        HIGHBAR_HANDLERS:{
            OpenDefault() {
				this.open('default');
			},
			Cover() {
				this.setFullScreenView();
			},
			Center() {
				this.setCenteredView();
			},
			Sleep() {
				YangRAM.API.APP.sleep(this.appid);
			},
			Close() {
				YangRAM.API.APP.close(this.appid);
			}
        },
        SMARTIAN_HELPER() {
            //
        },
        EXPLORER_CALLBACKS:{},
        BROWSER_TRIGGERS:{
            RemoveItem (id, sort, page, type, group){
				if(parseInt(id)){
					YangRAM.API.MSG.popup({
						title:'Remove Item?',
						content:'Are you sure to remove this item?',
						confirm:"Sure",
						cancel:"Cancel",
						done() {
							return self.submit(id, 'remove', sort, page, type, group);
						}
					});
				}else{
					YangRAM.API.MSG.notice({
						appid:__thisapp__.appid,
						title:'I\'m Sorry!',
						content:'Operation not supported!',
					});
				}
			},
			ToList(id, sort, page, type, group){
				if(group==undefined){
					__thisapp__.open('list/?sort='+sort+'&type='+type+'&page='+page, true);
				}else{
					__thisapp__.open('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page, true);
				}
			},
			ToTop (){
				__thisapp__.toTop();
			},
			CopyCode (){
				var code=__thisapp__.$('inputs[type=longtext]>textarea[name=code]').val();
				YangRAM.API.TXT.copy(code);
			},
			SaveCode (id, sort, page, type, group){
				self.submit(id, 'save', sort, page, type, group);
			}
        },
        onafterresize() {
            var sideHeight=(this.$('left list[type=menu]').outerHeight() || 0) + 20;
			var mainHeight=(this.$('main list[type=sheet]').outerHeight() || -40) + 40;
			var contentHeightMin=mainHeight > sideHeight ? mainHeight:sideHeight;
			contentHeightMin=contentHeightMin < 350 ? 350:contentHeightMin;
			if(this.viewstatus==1){
				contentHeightMin=YangRAM.API.APP.fsHeight() - 51 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 51:contentHeightMin;
				this.$('appcon').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('appmain').css('min-height', contentHeightMin);
			}else{
				this.$('appcon').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('appmain').css('min-height', contentHeightMin);
			}
			return this;
        }
    },
	onload(){
		//YangRAM.API.view.highlight.highlightAll();
		return this.resize();
	},
	main(){
		__thisapp__.listenEvents(self.events).regHeadBar(TAMBAR).loadStyle(function() {
            __thisapp__.loadURI(__thisapp__.__temp.href);
        });
	}};
const privates = {
	};
YangRAM.extends(__thisapp__, true, pm_5919743f7ba15);

});