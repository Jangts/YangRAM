static eventHandlers = {
        'left item[x-href]': {
            'click' (event) {
                var href = YangRAM.attr(this, 'x-href');
                __thisapp__.onlaunch(href);
            }
        },
        'main content item[x-href]': {
            'click' (event) {
                var href = YangRAM.attr(this, 'x-href');
                __thisapp__.onlaunch(href);
            }
        },
        'main content .item.folder': {
            'click' (event) {
                var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className != "sele" && elem.className != "rplc" && (elem.className != "name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type') == 'list') && elem.tagName != "INPUT") {
                    var href = YangRAM.attr(this, 'x-href');
                    var folder = parseInt(YangRAM.attr(this, 'x-id'));
                    __thisapp__.onlaunch(href, folder);
                }
            }
        },
        'main content .item[x-type=img]': {
            'click' (event) {
                var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className != "sele" && elem.className != "rplc" && (elem.className != "name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type') == 'list') && elem.tagName != "INPUT") {
                    PICS.call(this);
                }
            }
        },
        'main content .item[x-type=txt]': {
            'click' (event) {
                var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className != "sele" && elem.className != "rplc" && (elem.className != "name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type') == 'list') && elem.tagName != "INPUT") {
                    TXTS.call(this);
                }
            }
        },
        'main content .item[x-type=wav]': {
            'click' (event) {
                var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className != "sele" && elem.className != "rplc" && (elem.className != "name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type') == 'list') && elem.tagName != "INPUT") {
                    WAVS.call(this);
                }
            }
        },
        'main content .item': {
            'rclick' (event) {
                __thisapp__.$('main content .item').toggleClass('selected', false);
                YangRAM.toggleClass(this, 'selected');
            }
        },
        'main content .item .sele': {
            'click' (event) {
                YangRAM.toggleClass(this.parentNode, 'selected');
            }
        },
        'main content .item .rplc': {
            'click' (event) {
                CTX_MENU_HANDLERS.Replace.call(this.parentNode);
                //YangRAM.toggleClass(this.parentNode, 'selected');
            }
        },
        'main .main-content[x-type=tile] .name': {
            'click'(event){
                self.renameItem.call(this);
            } 
        },
        '.uploader-content .x-action': {
            'click' (event) {
                var num = parseInt(YangRAM.attr(this, 'x-index'));
                privates.toBeupload  = YangRAM.API.util.arr.removeByIndex(privates.toBeupload , num);
                var file = YangRAM.API.dom.closest(this, 'item');
                YangRAM.API.dom.remove(file);
            }
        },
        '.uploader-startbtn': {
            'mousedown' (event) {
                if (privates.toBeupload.length > 0) {
                    UPLOADER_START();
                } else {
                    alert(__('WORDS')("Please Select Files To Be Upload."));
                }
            }
        },
        '.uploader-cancelbtn': {
            'mousedown' (event) {
                if (privates.uploading) {
                    alert(__('WORDS')("Uploader Already In Working, Can Not Be Canceled!"));
                } else {
                    UPLOADER_HIDE();
                }
            }
        },
        '.uploader_completebtn': {
            'mousedown' () {
                __thisapp__.refresh();
            }
        },
        'top-vision .searcher input': {
            'enter' (event) {
                var kw = this.value;
                if (kw && kw != '') {
                    this.value = '';
                    __thisapp__.open('sch/?kw=' + encodeURIComponent(kw));
                }
            }
        },
        '.browse-popup .browse-img-src': {
            'click' () {
                var src = YangRAM.attr(this, 'data-val');
                YangRAM.API.TXT.copy(src);
            }
        },
        '.browse-popup .browse-img-img': {
            'click' () {
                var src = YangRAM.attr(this, 'data-val');
                var alt = YangRAM.$('.browse-img-title', this.parentNode).html();
                YangRAM.API.TXT.copy('<img src="' + src + '" alt="' + alt + '" />');
            }
        },
        '.browse-popup .browse-txt click': {
            'click' () {
                var src = YangRAM.attr(this, 'data-val');
                YangRAM.API.TXT.copy(src);
            }
        },
        '.browse-popup .browse-close': {
            'click' () {
                __thisapp__.$('.browse-popup').hide();
                self.palyer && (self.palyer.innerHTML = '');
            }
        },
        '.browse-popup .browse-txt-content': {
            'mousewheel' () {
                document.onmousewheel == null;
            }
        }
};
