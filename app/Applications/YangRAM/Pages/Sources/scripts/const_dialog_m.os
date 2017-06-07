const SHOW_TEMPLATES (themesList, themeInput, templateInput) {
        YangRAM.tools.showDialog({
                title: 'Choose a template',
                appid: __thisapp__.appid,
                css: 'dialog',
                height: 400
            }, function() {
                var dialogMain = this.render(themesList).contentarea;
                var dialogMain = this.render(themesList).contentarea;
        dialogMain.bindListener('.theme-list item.theme', 'mouseup', function() {
                var theme = YangRAM.attr(this, 'data-theme-alias');
                YangRAM.get({
                    url: __thisapp__.__dirs.getter + 'dialog/theme/' + theme,
                    done: function(txt) {
                        YangRAM.tools.hideMagicCube();
                        if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                        } else {
                            dialogMain.innerHTML = txt;
                            setTimeout(function() {
                                dialogMain.scrollBAR.resize();
                            }, 0);
                        }
                    },
                    fail(txt) {
                        console.log(txt);
                        YangRAM.tools.hideMagicCube();
                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                    }
                }).tools.showMagicCube();
            })
            .bindListener('.template-list item.themes', 'mouseup', function() {
                YangRAM.get({
                    url: __thisapp__.__dirs.getter + 'dialog/themes/',
                    done: function(txt) {
                        YangRAM.tools.hideMagicCube();
                        if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                        } else {
                            dialogMain.innerHTML = txt;
                            setTimeout(function() {
                                dialogMain.scrollBAR.resize();
                            }, 0);
                        }
                    },
                    fail(txt) {
                        console.log(txt);
                        YangRAM.tools.hideMagicCube();
                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                    }
                }).tools.showMagicCube();
            })
            .bindListener('.template-list item.template', 'dblclick', function() {
                themeInput.value = YangRAM.attr(this, 'data-theme-alias');
                templateInput.value = YangRAM.attr(this, 'data-template-path').replace(/^\/+/, '');
                YangRAM.tools.hideDialog();
            });
    });
};