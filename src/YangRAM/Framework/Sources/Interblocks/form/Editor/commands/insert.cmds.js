/*!
 * Interblocks Framework Source Code
 *
 * class forms/Editor
 * 
 * Date: 2015-09-04
 */
;
iBlock([
    '$_/form/Editor/'
], function(pandora, global, undefined) {
    var _ = pandora,
        isGetSelection = window.getSelection ? true : false,
        document = global.document,
        console = global.console;

    var commands = {
        'insert': function(val) {
            this.getRange();
            if (isGetSelection) {
                var fragment = this.range.originRange.createContextualFragment(val);
                this.range.originRange.deleteContents();
                this.range.originRange.insertNode(fragment);
            } else {
                document.selection.pasteHTML(val);
            }
            this.setRange(this._range);
            return this;
        },
        'p': function(val) {
            this.getRange().execCommand('formatblock', '<p>');
        },
        'blockquote': function(val) {
            this.getRange().execCommand('formatblock', '<blockquote>');
        },
        'hr': function(val) {
            this.execCommand('insert', '<hr />');
            this.range.collapse();
            this.execCommand('insert', '<p></p>');
        },
        'pagebreak': function(val) {
            this.execCommand('insert', '<hr style="page-break-after: always;" class="ic editor-pagebreak">');
        },
        'inserttime': function(val) {
            this.execCommand('insert', '<hr style="page-break-after: always;" class="ic editor-pagebreak">');
        },
        'insertdate': function(val) {
            this.execCommand('insert', '<hr style="page-break-after: always;" class="ic editor-pagebreak">');
        }
    };

    _.each(commands, function(cmd, handler) {
        _.form.Editor.regCommand(cmd, handler);
    });

    _.form.Editor.extends({
        insertHTML: function(val) {
            return this.execCommand('insert', val);
        }
    });
});