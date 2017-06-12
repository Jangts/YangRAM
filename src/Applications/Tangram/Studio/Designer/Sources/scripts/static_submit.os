static controls = {
		'preset-list'(){
			__thisapp__.open('list/');
		},
		'label-new'(){
			__thisapp__.open('form/emc/new/');
		},
		'label-save'(){
			var id = __thisapp__.$('section .edit-form').attr('x-cid');
			self.submitter(preset, id, 'sav');
		},
		'label-del'(){
			var id = __thisapp__.$('section .edit-form').attr('x-cid');
			if(parseInt(id)){
				YangRAM.API.MSG.popup({
					title: __LANG__.WORDS['Remove Item?'],
					content: __LANG__.WORDS['Are you sure to remove this item?'],
					confirm: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('confirm'),
					cancel: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('cancel'),
					done() {
						return self.submitter(preset, id, 'del');
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
	
static submitter(filename, operate, theme, type, sort, page){
		var url, title, content;
		var data = new FormData();
		data.append('id', filename);
		switch(operate){
			case 'sav':
			url = __thisapp__.__dirs.setter + 'submit/save/';
			title = 'Save Success';
			content = 'A Content Has Been Saved!';
			data = self.checkForm(data);
			break;
			case 'del':
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
					__thisapp__.open('list/'+theme+'/?type='+type+'&sort='+sort+'&page='+page, true);
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
	
static checkForm(data){
		var elem = __thisapp__.$('form-vision form');
		var form = new YangRAM.API.form.Data(elem[0]);
		var fields = form.checkValue().data;
		for(var i in fields){
			data.append(i, fields[i][0]);
		}
		return data;
	}