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
    '$_/form/Editor/commands/insert.cmds'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console;

    _.form.Editor.regCommand('insertfile', function(val, name) {
        if (_.util.bool.isStr(val)) {
            name = name || val;
            var html = 'Attachment : <a href="' + val + '" target="_blank" title="click to download" class="ic editor-attachment">' + name + '</a><br />';
            this.execCommand('insert', html);
            this.range.collapse();
            return this;
        }
        return this;
    });

    _.form.Editor.regCreater('insertfile', function() {
        var html = '<dialog class="ic editor-dialog">';
        html += '<span class="ic editor-title">Insert Files</span>';
        html += '<div class="ic editor-url">';
        html += '<label>Enter URL</label><input type="text" class="ic editor-input" placeholder="File URL" />';
        html += '</div>';
        html += '<input type="file" class="ic editor-files" value="" hidden="" />';
        html += '<div class="ic editor-btns">';
        html += '<input type="button" data-ib-cmd="insertfile" value="Insert Url"/>';
        html += '<input type="button" data-ib-cmd="uploadfile" value="Or Upload"/>';
        html += '</div>';
        html += '</dialog>';
        return html;
    });

    _.form.Editor.regDialog('insertfile', function(btn) {
        var dialog = _.dom.closest(btn, 'dialog');
        var input = _.query('.ic.editor-url .ic.editor-input', dialog)[0];
        if (input && input.value) {
            return input.value;
        }
        return null;
    });

    _.form.Editor.regDialog('uploadfile', function(btn) {
        var dialog = _.dom.closest(btn, 'dialog');
        var input = _.query('.ic.editor-files', dialog)[0];
        var that = this;
        input.onchange = function() {
            var file = this.files[0];
            if (that.attachment_type) {
                var preg = new RegExp('\.(' + that.attachment_type.join('|') + ')$', i);
                if (!preg.test(file)) {
                    return alert('Unsupported File Format');
                }
            }
            if (that.upload_maxsize) {
                if (file.size > that.upload_maxsize) {
                    return alert('Exceed Maximum Size Allowed Upload');
                }
            }
            if (_.util.bool.isFn(that.transfer)) {
                that.transfer([file], function(val, failed) {
                    if (failed) {
                        alert('attachment upload failed');
                    } else {
                        that.insertFile(val[0], file.name);
                    }
                    _.dom.toggleClass(that.loadmask, 'on', false);
                });
                _.dom.toggleClass(this.loadmask, 'on', true);
            } else {
                alert('No Upload Configuration');
            }
        }
        input.click();
    });

    _.form.Editor.extends({
        insertFile: function(val, name) {
            return this.execCommand('insertfile', val);
        }
    });
});