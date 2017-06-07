/*!
 * Interblocks Framework Source Code
 *
 * class forms/Editor
 * 
 * Date: 2015-09-04
 */
;
iBlock([
    '$_/util/bool.xtd',
    '$_/dom/',
    '$_/form/Editor/'
], function(pandora, global, undefined) {
    var _ = pandora,
        console = global.console;

    _.form.Editor.regCommand('createlink', function(val) {
        if (val && _.util.bool.isUrl(val.url)) {
            var url = 'temp.';
            url += (Date.parse(new Date()) * 1000 + Math.floor(Math.random() * 1000000000000000));
            url += '.com';
            if (this.getRange().type === 'Caret') {
                this.execCommand('insert', val.url);
            }
            this.getRange().execCommand('createlink', url);
            var a = _.query('a[href="' + url + '"]')[0];
            if (a) {
                a.href = val.url;
                a.href = val.url;
                if (val.isNew) {
                    a.target = '_blank';
                }
            }

        }
        return this;
    });

    _.form.Editor.regCreater('createlink', function() {
        var html = '<dialog class="ic editor-dialog">';
        html += '<span class="ic editor-title">Insert link</span>';
        html += '<div class="ic editor-url">';
        html += '<label>Enter URL</label><input type="text" class="ic editor-input" placeholder="http://www.yangram.com/interblocks/" />';
        html += '</div>';
        html += '<div class="ic editor-check">';
        html += '<input type="checkbox" class="ic editor-checkbox" checked="checked"> <label>Open in new tab</label>';
        html += '</div>';
        html += '<div class="ic editor-btns">';
        html += '<button type="button" data-ib-cmd="createlink">OK</button>';
        html += '</div>';
        html += '</dialog>';
        return html;
    });

    _.form.Editor.regDialog('createlink', function(btn) {
        var dialog = _.dom.closest(btn, 'dialog');
        var input = _.query('.ic.editor-url .ic.editor-input', dialog)[0];
        var checkbox = _.query('.ic.editor-check .ic.editor-checkbox', dialog)[0]
        if (input && input.value != '') {
            return {
                url: input.value,
                isNew: checkbox && checkbox.checked
            }
        }
        return null;
    });

    _.form.Editor.extends({
        createLink: function(val) {
            return this.execCommand('insertlink', val);
        }
    });
});