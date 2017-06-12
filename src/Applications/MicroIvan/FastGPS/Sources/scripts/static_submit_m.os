static {
    pageControl : {
        'page-list' () {
            __thisapp__.$('section[data-tab-name].curr popup-form').remove();
            __thisapp__.refreshTopVision();
        },
        'page-new' () {
            self.submit(0, 'edi');
        },
        'page-save' () {
            var id = __thisapp__.$('section.curr .content-edit-form').attr('data-content-id');
            self.submit(preset, id, 'sav');
        },
        'page-del' () {
            var id = __thisapp__.$('section.curr .content-edit-form').attr('data-content-id');
            if (parseInt(id)) {
                YangRAM.API.MSG.popup({
                    title: __('WORDS')("Remove Page?"),
                    content: __('WORDS')("Are you sure to remove this page?"),
                    confirm: __('WORDS')("Sure"),
                    cancel: __('WORDS')("Cancel"),
                    done() {
                        return self.submit(id, 'del');
                    }
                });
            } else {
                YangRAM.API.MSG.notice({
                    appid: __thisapp__.appid,
                    title: __('WORDS')("I'm Sorry!"),
                    content: __('WORDS')("Operation not supported!")
                });
            }
        },
        'page-fix' () {
            //YangRAM.tools.ExplorerSRC();
        },
        'page-setting' () {
            YangRAM.API.APP.launch(3, 'application/10/');
        }
    },
    submit (id, type) {
        var url, title, content;
        var data = new FormData();
        data.append('id', id);
        switch (type) {
            case 'edi':
                url = __thisapp__.__dirs.getter + 'open/form/';
                title = __('WORDS')("Save Success");
                content = __('WORDS')("A Page Has Been Saved!");
                break;
            case 'del':
                url = __thisapp__.__dirs.setter + 'submit/del/';
                title = __('WORDS')("Delete Success");
                content = __('WORDS')("A Page Has Been Deleted!");
                break;
            case 'dis':
                url = __thisapp__.__dirs.setter + 'submit/dis/';
                title = __('WORDS')("Disuse Using Success");
                content = __('WORDS')("A Page Has Been Disused!");
                break;
            case 'use':
                url = __thisapp__.__dirs.setter + 'submit/use/';
                title = __('WORDS')("Start Using Success");
                content = __('WORDS')("A Page Has Been Used!");
                break;
            case 'sav':
                url = __thisapp__.__dirs.setter + 'submit/sav/';
                title = __('WORDS')("Save Success");
                content = __('WORDS')("A Page Has Been Saved!");
                data = CHK_FORMDATA(data);
                break;
        }
        YangRAM.set({
            url: url,
            data: data,
            done (txt) {
                if (parseInt(txt)) {
                    YangRAM.API.MSG.notice({
                        appId: __thisapp__.appid,
                        title: title,
                        content: content
                    });
                    var qs = __thisapp__.$('section.curr main').attr('x-query-string');
                    switch (type) {
                        case 'del':
                            __thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            YangRAM.API.CHK.recycle();
                            break;
                        case 'dis':
                            __thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                        case 'use':
                            __thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                        case 'sav':
                            __thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                    }
                } else if (txt == '<CAN_NOT_FIND>') {
                    alert('Specifies No Existing Item');
                } else if (txt == '<ERROR>') {
                    console.log(txt);
                    alert('Unkonw Error');
                } else if (type == 'edi') {
                    if(id==0){
                        __thisapp__.tabviews.cutTo('DEFAULT');
                    }
                    __thisapp__.$('section[data-tab-name].curr popup-form').remove();
                    __thisapp__.$('section[data-tab-name].curr').append(txt);
                    __thisapp__.refreshTopVision();
                }
            },
            fail(txt) {
                console.log(url, txt);
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
            }
        });
    }
}