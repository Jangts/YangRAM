/*!
 * Interblocks Framework Source Code
 *
 * class forms/Editor
 * 
 * Date: 2015-09-04
 */
;
iBlock([
    '$_/dom/',
    '$_/form/Editor/'
], function(pandora, global, undefined) {
    var _ = pandora,
        console = global.console;

    _.form.Editor.regCommand('source', function() {
        this.getValue();
        if (this.isRich) {
            this.range = null;
            _.dom.setStyle(this.editarea, {
                display: 'none'
            });
            _.dom.setStyle(this.codearea, {
                display: 'block'
            });
        } else {
            _.dom.setStyle(this.codearea, {
                display: 'none'
            });
            _.dom.setStyle(this.editarea, {
                display: 'block'
            });
            if (this._range) {
                this.setRange(this._range);
            } else {
                this.setRange();
                this.range.collapse();
            }
        }
        this.isRich = Math.abs(this.isRich - 1);
        _.each(_.query('.ic.editor-tool', this.toolbar), function(i, el) {
            _.dom.toggleClass(this, 'invalid');
        });
        var elem = _.query('.ic.editor-tool.source', this.toolbar)[0];
        _.dom.toggleClass(elem, 'invalid');
        _.dom.toggleClass(elem, 'active');
        typeof this.options.onSwitch == 'function' && this.options.onSwitch();
        return this;
    });



    _.form.Editor.extends({
        swtichView: function() {
            this.execCommand('source');
            return this;
        }
    });
});