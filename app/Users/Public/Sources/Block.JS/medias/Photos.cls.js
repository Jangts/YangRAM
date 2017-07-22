/*!
 * Block.JS Framework Source Code
 *
 * class medias.Photos
 *
 * Date: 2017-04-06
 */
;
block([
    '$_/util/XHR.cls',
    '$_/util/query.xtd',
    '$_/util/bool.xtd',
    '$_/dom/'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console;

    declare('medias.Photos', {
        _init: function(stage, images) {
            this.Element = _.util.bool.isStr(stage) ? _.dom.query.byId(stage) : stage;
            _.dom.setStyle(this.Element, {
                cursor: 'default'
            });
            switch (typeof images) {
                case 'string':
                    this.loadBox();
                    this.loadJson(images);
                    break;
                case 'object':
                    this.loadBox();
                    if (_.util.bool.isArr(Array)) {
                        this.loadArray(images);
                    } else {
                        this.loadFolder(images);
                    }
                    break;
            };
        },
        images: [],
        loadArray: function(images) {
            this.images = images;
            this.length = images.length;
        },
        loadJson: function(url, callback) {
            var that = this;
            var callback = callback || function(arr) {
                that.loadArray(arr);
            };
            new _.data.XHR({
                url: url
            }).done(function(data) {
                var array = eval(data);
                if (_.util.bool.isArr(array)) {
                    callback(array);
                }
            }).send();
        },
        loadFolder: function(info) {
            this.images = [];
            if (info && info.imagePath) {
                this.length = info.totalImages || 36;
                this.cur = info.defaultImageNumber || this.cur;
                var type = info.imageExtension || 'png';
                var path = info.imagePath;
                for (var i = 1; i <= this.length; i++) {
                    this.images.push(path + i + '.' + type);
                }
            }
        }
    });
});