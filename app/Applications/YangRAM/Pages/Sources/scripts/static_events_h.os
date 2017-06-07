sstatic events = {
            'main item[href]': {
                'click': function() {
                    var href = YangRAM.attr(this, 'href');
                    __thisapp__.open(href);
                }
            },
            'tools.page-ctrl item': {
                'click': function() {
                    if (!YangRAM.API.DOM.hasClass(this, 'unavailable')) {
                        var type = YangRAM.attr(this, 'x-usefor');
                        typeof self.pageControl[type] == 'function' && self.pageControl[type]();
                    };
                }
            },
            'section.curr .form-input .page-upload-button': {
                'click': function() {
                    var input = YangRAM.$('input[name=cover]', this.parentNode)[0];
                    YangRAM.tools.pickFiles({
                        multiple: false,
                        filter: false,
                        type: 'image',
                        sfixs: false,
                        maxsize: 1024 * 1024 * 20,
                        done: function(files) {
                            __thisapp__.upload(files[0], {
                                before: function(data) {
                                    input.value = data.responseText;
                                },
                                progress: function(data) {
                                    if (data.lengthComputable) {
                                        input.value = Math.round(data.loaded * 100 / data.total) + '%';
                                    };
                                },
                                after: function(data) {
                                    input.value = data.responseText;
                                },
                                done: function(data) {
                                    eval(data.responseText)
                                    input.value = json.url;
                                },
                                fail(data) {
                                    input.value = '';
                                    alert('Upload Failed');
                                },
                                returnType: 'json'
                            });
                        },
                        fail(error, err_type) {
                            if (err_type) {
                                alert('Exceed Maximum Size Allowed Upload');
                            } else {
                                alert('Unsupported File Format');
                            }
                        }
                    });
                }
            },
            'section.curr inputs .pick-button': {
                'click': function() {
                    var theme = YangRAM.$('input[name=theme]', this.parentNode)[0];
                    var template = YangRAM.$('input[name=template]', this.parentNode)[0];
                    YangRAM.get({
                        url: __thisapp__.__dirs.getter + 'dialog/themes/',
                        done: function(txt) {
                            YangRAM.tools.hideMagicCube();
                            if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            } else {
                                SHOW_TEMPLATES(txt, theme, template);
                            }
                        },
                        fail(txt) {
                            console.log(txt);
                            YangRAM.tools.hideMagicCube();
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                        }
                    }).tools.showMagicCube();
                }
            }
    };