public onload () {
        if (this.tabviews == undefined) {
            var elem = this.$('tab-vision')[0];
            this.tabviews = this.OIMLElement.renderTabs(elem, {
                starttab: 'startpage',
                start(){
                    this.open(this.startTabName, 'default/startpage/');
                    return this;
                },
                onbeforeunload(tabName, oldTag, newTag) {
                    if (oldTag.trimed == 'SPC' + tabName || oldTag.trimed == 'GEC' + tabName) {
                        this.onbeforewrite(tabName, newTag);
                    } else {
						console.log(oldTag.trimed, tabName);
                        if (newTag.trimed == 'GEC' + tabName || newTag.trimed == 'SPC' + tabName) {
                            var content = __('WORDS')('Are you sure to go to the list page before save your editing?');
                        } else {
							console.log(newTag.trimed, tabName);
                            var content = __('WORDS')('Are you sure to open a new item before save the editing item?');
                        }
                        YangRAM.API.MSG.popup({
                            title: __('WORDS')('Abandon Editing?'),
                            content: content,
                            confirm: "Sure",
                            cancel: "Cancel",
                            done: () => {
								//console.log(tabName, newTag);
                                this.onbeforewrite(tabName, newTag);
                            }
                        });
                    }
                    return this;
                },
                onbeforewrite(tabName, tag) {
                    YangRAM.get({
                        url: __thisapp__.__dirs.getter + 'open/' + tag.origin,
                        done: (txt) => {
                            if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                alert('Something Wrong');
                                if (this.currTabName != 'STSTARTPAGE') {
                                    __thisapp__.open(this.currTabName);
                                }
                            } else {
                                this.write(tabName, tag, txt)
                                __thisapp__.$('section[data-tab-name=STARTPAGE] click[data-tab-name=' + tabName.toLowerCase() + ']').attr('href', tag.origin);
                                self.checkEditor(tag.path);
                                __thisapp__.resize().toTop();
                            }
                        },
                        fail: (txt) => {
                            if (txt.match('PHP Notice:')) {
                                alert('Something Wrong');
                                if (this.currTabName != 'STSTARTPAGE') {
                                    __thisapp__.open(this.currTabName);
                                }
                                //console.log(txt);
                            } else {
                                console.log(__thisapp__.__dirs.getter + 'open/' + tag.origin);
                                alert('Network Error');
                            }
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
                }
            });
        }
		return this.open(__thisapp__.__temp.href);
	};