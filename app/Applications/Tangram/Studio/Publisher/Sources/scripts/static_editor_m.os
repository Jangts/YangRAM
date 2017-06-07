static {
	editors : {},
	checkEditor(tabPath) {
        //console.log(tabPath);
		if (tabPath[0] != '' && tabPath[0] != 'default' && tabPath.length > 2) {
            var preset = tabPath[1].toUpperCase();
            var selector = '#' + __thisapp__.attr('id');
            selector += ' section[data-tab-name=' + preset.toUpperCase() + ']';
            selector += ' inputs[type=editor] textarea';
            //console.log(selector);
            self.editors[preset] = YangRAM.API.form.careatEditors(selector, {
                themeType: 'default',
                border: {
                    color: '#DDD'
                },
                toolbarType: 'complete',
                fragments: [{
                        name: '分页符',
                        code: '{{@page_break}}'
                    }],
                onSwitch: function() {},
                uploader: {
                    maxsize: 1024 * 1024 * 20,
                    sfixs: false,
                    transfer: function(files, inserter) {
                        var total = files.length;
                        var loaded = 0;
                        var failed = 0;
                        var images = [];

                        function listen() {
                            if (loaded + failed == total) {
                                inserter(images, failed);
                            }
                        };
                        for (var i = 0; i < files.length; i++) {
                            __thisapp__.upload(files[i], {
                                done(data) {
                                    try {
                                        var json = JSON.parse(data.responseText);
                                        if (json.code == 200) {
                                            loaded++;
                                            images.push(json.file.url);
                                        } else {
                                            failed++;
                                            console.log('File Upload Failed');
                                        }
                                    } catch (e) {
                                        failed++;
                                        console.log('File Upload Failed');
                                    }
                                    listen();
                                },
                                fail(data) {
                                    failed++;
                                    listen();
                                },
                                returnType: 'json'
                            });
                        }
                    }
                }
            });
        }
	}
};