/*!
 * Block.JS Framework Source Code
 *
 * class forms/Editor
 * 
 * Date: 2015-09-04
 */
;
block([
    '$_/dom/',
    '$_/util/Color.cls'
], function(pandora, global, undefined) {
    var _ = pandora,
        cache = pandora.locker,
        console = global.console;

    var rbgaToHexadecimal = function(rgba) {
            var arr = rgba.split(/\D+/);
            var num = Number(arr[1]) * 65536 + Number(arr[2]) * 256 + Number(arr[3]);
            var hex = num.toString(16);
            while (hex.length < 6) {
                hex = "0" + hex;
            }
            return "#" + hex.toUpperCase();
        },
        inheritDecoration = function(node, textDecorationLine) {
            while (node != undefined && node != null) {
                var _inheritDecoration = _.dom.getStyle(node).textDecorationLine;
                if (_inheritDecoration && (_inheritDecoration == textDecorationLine)) {
                    return true;
                }
                node = node.parentNode;
            }
            return false;
        },
        checkFontFormat = function(style) {
            var range = this.selection.getRange();
            if (range && range.commonElem) {
                _.each(_.query('.bc.editor-pick li', this.toolarea), function(i, el) {
                    _.dom.toggleClass(this, 'selected', false);
                });
                selector = ".fontname .bc.editor-font[data-ib-val=\"" + style.fontFamily + "\"]";
                selector += ", .fontsize .bc.editor-font[data-ib-val=\"" + style.fontSize + "\"]";
                selector += ", .forecolor .bc.editor-color[data-ib-val=\"" + rbgaToHexadecimal(style.color) + "\"]";
                selector += ", .backcolor .bc.editor-color[data-ib-val=\"" + rbgaToHexadecimal(style.backgroundColor) + "\"]";
                _.each(_.query(selector, this.toolarea), function(i, el) {
                    _.dom.toggleClass(this, 'selected', true);
                });
            }
        },
        checkFormat = function() {
            var range = this.selection.getRange();
            if (range && range.commonElem) {
                _.each(_.query('.bold, .italic, .underline, .strikethrough, .justifyleft, .justifycenter, .justifyright, .justifyfull, .insertunorderedlist, .insertorderedlist', this.toolarea), function(i, el) {
                    _.dom.toggleClass(this, 'active', false);
                });
                var style = _.dom.getStyle(range.commonElem);
                var selector = [];
                if (style.fontWeight == 'bold') {
                    selector.push('.bold');
                }
                if (style.fontStyle == 'italic') {
                    selector.push('.italic');
                }
                //console.log(style.textDecoration, style.textDecorationLine, inheritDecoration(range.commonElem, 'underline'), inheritDecoration(range.commonElem, 'line-through'));
                if (inheritDecoration(range.commonElem, 'line-through')) {
                    selector.push('.strikethrough');
                }
                if (inheritDecoration(range.commonElem, 'underline')) {
                    selector.push('.underline');

                }
                switch (style.textAlign) {
                    case 'start':
                    case 'left':
                        selector.push('.justifyleft');
                        break;
                    case 'center':
                        selector.push('.justifycenter');
                        break;
                    case 'end':
                    case 'right':
                        selector.push('.justifyright');
                        break;
                    case 'justify':
                        selector.push('.justifyfull');
                        break;
                }
                if (_.dom.closest(range.commonElem, 'ul')) {
                    selector.push('.insertunorderedlist');
                }
                if (_.dom.closest(range.commonElem, 'ol')) {
                    selector.push('.insertorderedlist');
                }
                // if ((range.commonElem.tagName === 'A') || _.dom.closest(range.commonElem, 'a')) {
                //     selector.push('.createlink');
                // }
                if (selector.length > 0) {
                    _.each(_.query(selector.join(', '), this.toolarea), function(i, el) {
                        _.dom.toggleClass(this, 'active', true);
                    });
                }
                checkFontFormat.call(this, style);
            }
        },
        checkStatus = function() {
            var range = this.selection.getRange();
            if (range && range.commonElem) {
                var style = _.dom.getStyle(range.commonElem),
                    node = _.dom.closest(range.commonElem, 'table'),
                    row = _.dom.closest(range.commonElem, 'tr'),
                    cell = _.dom.closest(range.commonElem, 'td', true);
                _.query('.bc.editor-fontstatus .bc.editor-fsize-input', this.statebar)[0].value = style.fontSize;
                _.query('.bc.editor-fontstatus .bc.editor-color-input', this.statebar)[0].value = _.util.Color.rgbFormat(style.color, 'hex6');
                if (node && row) {
                    _.query('.bc.editor-tablestatus', this.statebar)[0].style.display = 'block';

                    var rowslen = node.rows.length,
                        colslen = row.cells.length;
                    this.selectedTable = node;
                    this.selectedTableRow = row;
                    this.selectedTableCell = cell;
                    //console.log([node]);
                    _.query('.bc.editor-tablestatus .bc.editor-tablewidth-input', this.statebar)[0].value = node.offsetWidth;
                    _.query('.bc.editor-tablestatus .bc.editor-rowslen', this.statebar)[0].value = rowslen;
                    _.query('.bc.editor-tablestatus .bc.editor-colslen', this.statebar)[0].value = colslen;
                    _.query('.bc.editor-tablestatus .bc.editor-border-input', this.statebar)[0].value = node.border || 0;
                } else {
                    _.query('.bc.editor-tablestatus', this.statebar)[0].style.display = 'none';
                }
                if (this.selectedImage) {
                    _.query('.bc.editor-imagestatus', this.statebar)[0].style.display = 'block';
                    _.query('.bc.editor-imagestatus .bc.editor-imgwidth-input', this.statebar)[0].value = this.selectedImage.offsetWidth;
                    _.query('.bc.editor-imagestatus .bc.editor-imgheight-input', this.statebar)[0].value = this.selectedImage.offsetHeight;
                    _.query('.bc.editor-imagestatus .bc.editor-border-input', this.statebar)[0].value = this.selectedImage.border || 0;
                    var nodes = _.query('.bc.editor-imagestatus .bc.editor-imgfloat', this.statebar),
                        select = this.selectedImage.style.float ? this.selectedImage.style.float : 'none';
                    _.each(nodes, function(i, node) {
                        _.dom.toggleClass(node, 'active', false);
                    });
                    console.log(select, _.util.arr.has(['left', 'right', 'none'], select));
                    if (_.util.arr.has(['left', 'right', 'none'], select) === false) {
                        select = 'none';
                    }
                    _.dom.toggleClass(_.query('.bc.editor-imagestatus .bc.editor-imgfloat[data-float=' + select + ']', this.statebar)[0], 'active', true);
                    if (!this.selectedImage.border) {
                        _.dom.setAttr(this.selectedImage, '_selected', '_selected');
                    }
                } else {
                    _.query('.bc.editor-imagestatus', this.statebar)[0].style.display = 'none';
                }
            }
        };
    cache.save({
        format: checkFormat,
        status: checkStatus
    }, 'EDITOR_CHECKS');
});