public loadWidgets() {
        this.LEFT = YangRAM.create('section', __thisapp__.widgets, {
            className: 'left-widgets',
        });
        this.RIGHT = YangRAM.create('section', __thisapp__.widgets, {
            className: 'right-widgets',
        });
        YangRAM.get({
            url: __thisapp__.__dirs.getter + 'module/widgets',
            done(txt) {
                if (txt == '<ERROR>' || txt.match('PHP Notice:') || txt.match('{"status":"error"')) {
                    console.log(txt);
                    //
                } else {
                    var customs = JSON.parse(txt);
                    self.widgetsLeft = YangRAM.API.util.arr.merge(self.widgetsLeft, customs);
                    YangRAM.API.util.arr.each(self.widgetsLeft, function(i, widget) {
                        self.widgets[widget.alias] = {
                            title: widget.title,
                            appid: widget.app_id,
                            widget: YangRAM.create('widget', __thisapp__.LEFT, {
                                html: '<atip>' + self.loading + '</atip>'
                            })
                        }
                        var url = YangRAM.URI + widget.app_id + '/' + widget.api_method + (widget.api_token ? ('?token=' + widget.api_token) : '');
                        YangRAM.get({
                            url: url,
                            done(txt) {
                                if (txt == '' || txt == '<ERROR>' || txt.match('PHP Notice:') || txt.match('{"status":"error"')) {
                                    self.widgets[widget.alias].innerHTML = '<atip>加载失败！<atip>';
                                } else {
                                    try {
                                            var data = JSON.parse(txt);
                                            if (YangRAM.API.isFn(self.widgetRenderers[data.type])) {
                                                self.widgetRenderers[data.type](widget.alias, widget.type, data.data, data.height);
                                            }
                                        } catch (e) {
                                            console.log(url);
                                        }
                                }
                            },
                            fail(txt) {
                                console.log(url);
                                self.widgets[widget.alias].innerHTML = '<atip>加载失败！<atip>';
                            }
                        });
                    });
                }
            },
            fail(txt) {
                console.log(txt);
            },
        });
        return this;
    };