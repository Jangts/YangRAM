static {
	controls : {
		'preset-list': function() {
            if (__thisapp__.tabviews.currTabName == 'GENERAL') {
                __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName);
            } else {
                __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName);
            }
        },
        'content-new': function() {
            if (__thisapp__.tabviews.currTabName == 'GENERAL') {
                __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '/new/');
            } else {
                __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName + '/new/');
            }
        },
        'content-save': function() {
            var preset = __thisapp__.tabviews.currTabName;
            var id = __thisapp__.$('section.curr form-vision').attr('x-cid'),
                sort = __thisapp__.$('section.curr form-vision').attr('x-sort'),
                page = __thisapp__.$('section.curr form-vision').attr('x-page'),
                status = __thisapp__.$('section.curr form-vision').attr('x-status'),
                cls = __thisapp__.$('section.curr form-vision').attr('x-cls');
            self.submit(preset, id, 'sav', sort, page, status, cls);
        },
        'content-del': function() {
            var preset = __thisapp__.tabviews.currTabName;
            var id = __thisapp__.$('section.curr form-vision').attr('x-cid'),
                sort = __thisapp__.$('section.curr form-vision').attr('x-sort'),
                page = __thisapp__.$('section.curr form-vision').attr('x-page'),
                status = __thisapp__.$('section.curr form-vision').attr('x-status'),
                cls = __thisapp__.$('section.curr form-vision').attr('x-cls');
            if (parseInt(id)) {
                YangRAM.API.MSG.popup({
                    title: __('Remove Item?'),
                    content: __('Are you sure to remove this item?'),
                    confirm: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('confirm'),
					cancel: YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('cancel'),
                    done: function() {
                        if (preset == __thisapp__.tabviews.currTabName) {
                            return self.submit(preset, id, 'del', sort, page, status, cls);
                        }
                        alert(__('Reference Error'));
                    }
                });
            } else {
                YangRAM.API.MSG.notice({
                    appid: __thisapp__.appid,
                    title: __('WORDS')('I\'m Sorry!'),
                    content:__('WORDS')('Operation not supported!'),
                });
            }
        },
        'content-view': function() {
            var preset = __thisapp__.tabviews.currTabName;
            var elem = __thisapp__.$('section.curr form-vision');
            var id = elem.attr('x-cid');
            var theme = elem.attr('x-theme');
            var template = elem.attr('x-template');
            if (theme && theme != '' && template && template != '') {
                var form = document.createElement("form");
                form.action = YangRAM.VirtualURI + 'preview/' + preset + '/' + theme + '/?temp=' + template;
                form.method = 'post';
                form.target = '_blank';
                YangRAM.create('input', form, {
                    type: 'hidden',
                    name: 'ID',
                    value: id,
                });
                if (self.editors[preset]) {
                    for (var e = 0; e < self.editors[preset].length; e++) {
                        self.editors[preset][e].getValue();
                    }
                };
                var fields = new YangRAM.API.form.Data(elem[0]).checkValue().data;
                for (var i in fields) {
                    YangRAM.create('input', form, {
                        type: 'hidden',
                        name: i,
                        value: fields[i][0],
                    });
                }
                form.submit();
            } else {
                alert(__('WORDS')('No Default template!'));
            }
        },
        'content-base': function() {
            self.switchContentInfoSection('content-base')
        },
        'content-prop': function() {
            self.switchContentInfoSection('content-prop')
        },
        'content-rele': function() {
            self.switchContentInfoSection('content-rele')
        },
        'mini-explorer': function() {
            YangRAM.tools.ExplorerSRC();
        },
        'pub-setting': function() {
            YangRAM.API.APP.launch(3, 'set-application-permissions/1002/')
        }
	},
    switchContentInfoSection (section){
		__thisapp__.$('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .content-info-section').hide();
		__thisapp__.$('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .'+section).show();
        console.log('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .'+section);
	},
	submit (preset, id, type, sort, page, status, cls) {
		var url, title, content;
        var data = new FormData();
        preset = preset.toLowerCase();
        data.append('SET_ALIAS', preset);
        data.append('ID', id);
        switch (type) {
            case 'sav':
                url = __thisapp__.__dirs.setter + 'submit/sav/';
                title = __('WORDS')('Save Success');
                content = __('WORDS')('A Content Has Been Saved!');
                data = self.checkForm(data, preset);
                break;
            case 'pub':
                url = __thisapp__.__dirs.setter + 'submit/pub/';
                title = __('WORDS')('Published');
                content = __('WORDS')('A Content Has Been Published!');
                data = self.checkForm(data, preset);
                break;
            case 'del':
                url = __thisapp__.__dirs.setter + 'submit/rmv/';
                title = __('WORDS')('Remove Success');
                content = __('WORDS')('A Content Has Been Removed!');
                break;
        }
        YangRAM.set({
            url: url,
            data: data,
            done: function(txt) {
                //console.log(txt);
                if (parseInt(txt)) {
                    YangRAM.API.MSG.notice({
                        appId: __thisapp__.appid,
                        title: title,
                        content: content
                    });
                    if (preset == 'general') {
                        if (status == undefined) {
                            __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&page=' + page, true);
                        } else {
                            __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&group=' + status + '&page=' + page, true);
                        }
                    } else {
                        __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&stts=' + status + '&cat=' + cls + '&page=' + page, true);
                    }
                    //YangRAM.API.CHK.recycle();
                } else if (txt == '<CAN_NOT_FIND>') {
                    alert(__('WORDS')('Specifies No Existing Item'));
                } else {
                    console.log(url, preset, id, txt);
                    alert('Unkonw Error');
                }
            },
            fail(txt) {
                console.log(txt);
                alert('Network Error');
            }
        });
	},
	checkForm (data, preset){
		var elem = __thisapp__.$('section.curr'),
        preset = preset.toUpperCase();
        if (elem.attr('data-tab-name') === preset) {
            if (self.editors[preset]) {
                for (var e = 0; e < self.editors[preset].length; e++) {
                    self.editors[preset][e].getValue();
                }
            }else{
            }
            var form = new YangRAM.API.form.Data(elem[0]);
            var fields = form.checkValue().data;
            for (var i in fields) {
                data.append(i, fields[i][0]);
            }
        }
        return data;
	}
}