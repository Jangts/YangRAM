public {
    onload() {
        if (this.tabviews == undefined) {
            var elem = this.$('tab-vision')[0];
            this.tabviews = this.OIMLElement.renderTabs(elem, {
                starttab: 'DEFAULT',
                start(){
                    this.open(this.startTabName, 'default/startpage/');
                    return this;
                },
                onbeforeunload(tabName, oldTag, newTag) {
                    return this.onbeforewrite(tabName, newTag);
                },
                onbeforewrite(tabName, tag) {
                    //console.log(tabName, tag);
                    YangRAM.get({
                        url: __thisapp__.__dirs.getter + 'open/' + tag.origin,
                        done: (txt) => {
                            if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                console.log(txt);
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            } else {
                                this.write(tabName, tag, txt)
                                __thisapp__.$('section[data-tab-name=STARTPAGE] click[data-tab-name=' + tabName.toLowerCase() + ']').attr('href', tag.origin);
                                //self.checkEditor(tag.path);
                                __thisapp__.resize().toTop();
                            }
                        },
                        fail: (txt) => {
                            console.log(txt);
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                        }
                    });
                    return this;
                },
                onbeforecut(tag) {
                    __thisapp__.refreshTopVision(tag.path);
                    return this;
                },
                onaftercut(tag) {
                    __thisapp__.setSource(tag.origin).resize();
                    return this;
                },
            });
        }
        return this.open(__thisapp__.__temp.href);
    }
}