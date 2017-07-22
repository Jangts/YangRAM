const PICS () {
        var id = YangRAM.attr(this, 'x-id');
        self.getItemInfo('img', id, function(info) {
            var width = YangRAM.API.APP.width() - 380;
            var height = YangRAM.API.APP.fsHeight() - 80;
            var _height;
            var src = YangRAM.RequestDIR + 'files/img/' + info["ID"] + '.' + info["SUFFIX"];
            var html = '<el class="browse-close">×</el>';
            html += '<v class="browse-pic" style="width:' + width + 'px; height:' + height + 'px; "><img class="browse-img-file" src="' + src + '?mt=' + Date.parse(info["KEY_MTIME"]) + '" /></v>';
            html += '<scroll-vision scroll-y="true" class="browse-img-info" style="height:' + height + 'px;">';
            html += '<v class="browse-img-infolist browse-img-title">' + info["FILE_NAME"] + '</v>';
            html += '<v class="browse-img-infolist browse-img-width"><el>' + __('ATTRS')("MIME") + ':</el>' + info["MIME"] + '(.' + info["SUFFIX"] + ')</v>';
            html += '<v class="browse-img-infolist browse-img-width"><el>' + __('ATTRS')("Width") + ':</el>' + info["WIDTH"] + 'px</v>';
            html += '<v class="browse-img-infolist browse-img-height"><el>' + __('ATTRS')("Height") + ':</el>' + info["HEIGHT"] + 'px</v>';
            html += '<v class="browse-img-infolist browse-img-ctime"><el>' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Create Time") + ':</el>' + info["KEY_CTIME"] + '</v>';
            html += '<v class="browse-img-infolist browse-img-mtime"><el>' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Modify Time") + ':</el>' + info["KEY_MTIME"] + '</v>';
            html += '<v class="browse-img-dashed"></v>';
            html += '<v class="browse-img-control browse-img-src" data-val="' + src + '">' + __('WORDS')("Copy URL") + ' (' + __('WORDS')("natural size") + ')</v>';
            var OrginWidth = parseInt(info["WIDTH"]);
            var OrginHeight = parseInt(info["HEIGHT"]);
            if (OrginWidth > 1920) {
                _height = parseInt(OrginHeight * 1920 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '">' + __('WORDS')("Copy URL") + ' (1920 x ' + _height + ')</v>';
            }
            if (OrginWidth > 1200) {
                _height = parseInt(OrginHeight * 1200 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_1200.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (1200 x ' + _height + ')</v>';
            }
            if (OrginWidth > 1000) {
                _height = parseInt(OrginHeight * 1000 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_1000.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (1000 x ' + _height + ')</v>';
            }
            if (OrginWidth > 800) {
                _height = parseInt(OrginHeight * 800 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_800.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (800 x ' + _height + ')</v>';
            }
            if (OrginWidth > 640) {
                _height = parseInt(OrginHeight * 640 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_640.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (640 x ' + _height + ')</v>';
            }
            if (OrginWidth > 480) {
                _height = parseInt(OrginHeight * 480 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_480.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (480 x ' + _height + ')</v>';
            }
            if (OrginWidth > 360) {
                _height = parseInt(OrginHeight * 360 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_360.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (360 x ' + _height + ')</v>';
            }
            if (OrginWidth > 240) {
                _height = parseInt(OrginHeight * 240 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_240.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (240 x ' + _height + ')</v>';
            }
            if (OrginWidth > 120) {
                _height = parseInt(OrginHeight * 120 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_120.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (120 x ' + _height + ')</v>';
            }
            if (OrginWidth > 30) {
                _height = parseInt(OrginHeight * 30 / OrginWidth);
                html += '<v class="browse-img-control browse-img-src" data-val="' + src + '_30.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (30 x ' + _height + ')</v>';
            }
            html += '<v class="browse-img-control browse-img-img" data-val="' + src + '">' + __('WORDS')("Copy HTML Code") + '</v>';
            html += '</v>';
            var popup = YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('v', __thisapp__.view, {
                className: 'browse-popup',
            });
            popup.innerHTML = html;
            popup.style.display = 'block';
        });
    };
const TXTS () {
        var id = YangRAM.attr(this, 'x-id');
        self.getItemInfo('txt', id, function(info) {
            var left = (YangRAM.API.APP.width() - 1000) / 2;
            var right = left - 15;
            var height = YangRAM.API.APP.fsHeight() - 160;
            var src = YangRAM.RequestDIR + 'files/txt/' + info["ID"] + '.' + info["SUFFIX"];
            var html = '<el class="browse-close"  style="right:' + right + 'px; ">×</el>';
            html += '<v class="browse-txt" style="left:' + left + 'px;">';
            html += '<header class="browse-txt-title">' + info["FILE_NAME"] + '</header>';
            html += '<p class="browse-txt-info"><el title="Create Time">[' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Create Time") + ']' + info["KEY_CTIME"] + '</el>';
            html += '<el title="Modify Time">[' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Modify Time") + ']' + info["KEY_MTIME"] + '</el>';
            html += '<el><click data-val="' + src + '">[' + __('WORDS')("Copy File URL") + ']</click></el></p>';
            html += '<textarea class="browse-txt-content" style="height:' + height + 'px;" readonly>' + info["FILE_CONTENT"] + '</textarea>';
            html += '</v>';
            var popup = YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('v', __thisapp__.view, {
                className: 'browse-popup',
            });
            popup.innerHTML = html;
            popup.style.display = 'block';
        });
    };
const WAVS () {
        var id = YangRAM.attr(this, 'x-id');
        self.getItemInfo('wav', id, function(info) {
            var src = YangRAM.RequestDIR + 'files/wav/' + info["ID"] + '.' + info["SUFFIX"];
            var html = '<el class="browse-close">×</el>';
            html += '<audio class="browse-wav" src="' + src + '" controls autoplay></audio>';
            self.palyer = YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('v', __thisapp__.view, {
                className: 'browse-popup',
            });
            self.palyer.innerHTML = html;
            self.palyer.style.display = 'block';
        });
    };

static getItemInfo (type, id, callback) {
        YangRAM.get({
            url: __thisapp__.__dirs.getter + 'fileinfo/' + type + '/' + id + '/',
            done(txt) {
                if (txt.match(/^\{/)) {
                    var info=JSON.parse(txt);
                    callback(info);
                } else if (txt == '<ERROR404>') {
                    alert('File Not Exists');
                    __thisapp__.refresh();
                } else {
                    console.log(txt);
                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                }
            },
            fail(txt) {
                console.log(txt);
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
            }
        });
};
