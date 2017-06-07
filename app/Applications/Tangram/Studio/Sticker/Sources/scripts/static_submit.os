static controls = {
		'label-list' (){
			__thisapp__.open('list/');
		},
		'label-new' (){
			__thisapp__.open('form/emc/new/');
		},
		'label-save' (){
			var id = __thisapp__.$('form-vision').attr('x-cid'),
                sort = __thisapp__.$('form-vision').attr('x-sort'),
                page = __thisapp__.$('form-vision').attr('x-page'),
                type = __thisapp__.$('form-vision').attr('x-type'),
                group = __thisapp__.$('form-vision').attr('x-group');
			self.submit(id, 'save', sort, page, type, group);
		},
		'label-del' (){
			var id = __thisapp__.$('form-vision').attr('x-cid'),
                sort = __thisapp__.$('form-vision').attr('x-sort'),
                page = __thisapp__.$('form-vision').attr('x-page'),
                type = __thisapp__.$('form-vision').attr('x-type'),
                group = __thisapp__.$('form-vision').attr('x-group');
			if(parseInt(id)){
				YangRAM.API.MSG.popup({
					title: __LANG__.WORDS['Remove Item?'],
					content: __LANG__.WORDS['Are you sure to remove this item?'],
					confirm: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('confirm'),
					cancel: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('cancel'),
					done() {
						return self.submit(id, 'remove', sort, page, type, group);
					}
				});
			}else{
				YangRAM.API.MSG.notice({
					appid: __thisapp__.appid,
					title: __LANG__.WORDS['I\'m Sorry!'],
					content: __LANG__.WORDS['Operation not supported!'],
				});
			}
		}
	}
	
static submit(id, operate, sort, page, type, group){
		var url, title, content;
		var data = new FormData();
		data.append('id', id);
		switch(operate){
			case 'save':
			url = __thisapp__.__dirs.setter + 'submit/save/';
			title = 'Save Success';
			content = 'A Content Has Been Saved!';
			data = checkForm(data);
			break;
			case 'remove':
			url = __thisapp__.__dirs.setter + 'submit/remove/';
			title = 'Remove Success';
			content = 'A Content Has Been Removed!';
			break;
		}
		YangRAM.set({
			url: url,
			data: data,
			done(txt) {
				if(parseInt(txt)){
					YangRAM.API.MSG.notice({
						appId: __thisapp__.appid,
						title: title,
						content: content
					});
					if(group==undefined){
						__thisapp__.open('list/?sort='+sort+'&type='+type+'&page='+page, false, true);
					}else{
						//console.log('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page);
						__thisapp__.open('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page, false, true);
					}
					YangRAM.API.CHK.recycle();
				}else if (txt == '<CAN_NOT_FIND>') {
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
	};
	
	const checkForm(data, preset){
		var elem = __thisapp__.$('form-vision form');
		var form = new YangRAM.API.form.Data(elem[0]);
		var fields = form.checkValue().data;
		for(var i in fields){
			data.append(i, fields[i][0]);
		}
		return data;
	}