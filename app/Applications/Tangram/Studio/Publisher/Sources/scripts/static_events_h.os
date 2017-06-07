static events = {
	'ico.pub-icon': {
            'click': function() {
                __thisapp__.open('default/startpage/');
            }
        },
        'main item[href]': {
            'click': function() {
                var href = YangRAM.attr(this, 'href');
                __thisapp__.open(href);
            }
        },
        'tools.pub-ctrl item': {
            'click': function() {
                if (!YangRAM.API.DOM.hasClass(this, 'unavailable')) {
                    var type = YangRAM.attr(this, 'x-usefor');
                    typeof self.controls[type] == 'function' && self.controls[type]();
                };
            }
        },
        'section.curr inputs[type=text] click[type=pick][picker=timepicker]': {
            'click': function() {
                var fieldname = YangRAM.attr(this, 'name');
                var input = YangRAM.$('input[name=' + fieldname + ']', this.parentNode);
                var type = YangRAM.attr(this, 'pick-data-type') || 'datetime';
                    YangRAM.tools.pickTime(input.val(), type, (val) => {
                        input.val(val);
                    });
            }
        },
        'section.curr inputs[type=text] click[type=pick][picker=uploader]': {
            'click': function() {
                var fieldname = YangRAM.attr(this, 'name');
                var type = YangRAM.attr(this, 'filetype');
                var sfixs = false;
                var input = YangRAM.$('input[name=' + fieldname + ']', this.parentNode)[0];
                if (!type || type == 'all') {
                    type = null;
                    sfixs = true;
                }
                YangRAM.tools.pickFiles({
                    multiple: false,
                    filter: false,
                    type: type,
                    sfixs: sfixs,
                    maxsize: 1024 * 1024 * 20,
                    done: function(files) {
                        console.log(this);
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
                                try {
                                    var json = JSON.parse(data.responseText);
                                    switch (json.code) {
                                        case 200:
                                            var text = json.file.url;
                                            break;
                                        case '703.61':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_INI_SIZE');
                                            break;
                                        case '703.62':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_FORM_SIZE');
                                            break;
                                        case '703.63':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_PARTIAL');
                                            break;
                                        case '703.64':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_FILE');
                                            break;
                                        case '703.66':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_TMP_DIR');
                                            break;
                                        case '703.67':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_CANT_WRITE');
                                            break;
                                        case '703.68':
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_EXTENSION');
                                            break;
                                        default:
                                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('Unknown Result');
                                            console.log(json);
                                    }
                                } catch (e) {
                                    var text = data.responseText;
                                }
                                input.value = text;
                            },
                            fail(data) {
                                input.value = '';
                                alert('Upload Failed');
                                console.log(data);
                            },
                            returnType: 'json'
                        });
                    },
                    fail(error, err_type) {
                        if (err_type) {
                            alert(__('WORDS')('Exceed Maximum Size Allowed Upload'));
                        } else {
                            alert(__('WORDS')('Unsupported File Format'));
                        }
                    }
                });
            }
        },
        'input, textarea, .ib-editor[data-editor-id]': {
            'mouseup': function() {
                __thisapp__.Input = this;
            }
        },
        'inputs[inpreview] input': {
            'mouseover': function() {
                var parent = this.parentNode;
                var status = YangRAM.attr(parent, 'inpreview');
                if (status != 'inpreview') {
                    var src = this.value;
                    var url = YangRAM.attr(parent, 'preview-src');
                    if (src) {
                        if (src != url) {
                            var ctx = YangRAM.$('image-vision', this.parentNode).html('').get(0);
                            var img = new YangRAM.API.medias.Image({
                                src: src,
                                context: ctx,
                                onload: function() {
                                    if (this.width > this.height) {
                                        var w = this.width < 250 ? this.width : 250;
                                        var h = this.height / this.width * w;
                                    } else {
                                        var h = this.height < 250 ? this.height : 250;
                                        var w = this.width / this.height * h;
                                    }
                                    YangRAM.setStyle(ctx, {
                                        width: w,
                                        height: h
                                    });
                                    YangRAM.attr(parent, 'inpreview', 'inpreview');
                                    //console.log(src, this.width, this.height);
                                },
                                onerror: function() {
                                    YangRAM.attr(parent, 'preview-src', '');
                                }
                            });
                            YangRAM.attr(parent, 'preview-src', src);
                        } else if (url) {
                            YangRAM.attr(parent, 'inpreview', 'inpreview');
                        }
                    }
                }
            },
            'mouseout': function() {
                var parent = this.parentNode;
                var status = YangRAM.attr(parent, 'inpreview');
                if (status == 'inpreview') {
                    YangRAM.attr(parent, 'inpreview', 'outpreview');
                }
            }

        }
    };