/*!
 * Interblocks Framework Source Code
 *
 * commands forms/Editor
 * 
 * Date: 2015-09-04
 */
;
iBlock([
    '$_/dom/',
    '$_/form/Range.Cls',
    '$_/form/Editor/'
], function(pandora, global, undefined) {
    var _ = pandora,
        cache = pandora.locker,
        console = global.console;

    var presets = [
            'bold', 'copy', 'cut', 'delete',
            'indent', 'italic', 'insertorderedlist', 'insertunorderedlist',
            'justifycenter', 'justifyfull', 'justifyleft', 'justifyright',
            'outdent', 'redo', 'removeformat', 'selectall',
            'strikethrough', 'subscript', 'superscript',
            'underline', 'undo', 'unlink'
        ],
        customs = {
            'paste': function(val) {
                if (global.clipboardData) {
                    this.getRange().execCommand('paste', null);
                } else {
                    alert('Browser does not support paste, please use Ctrl+V');
                };
            },
            'pasteastext': function(val) {
                if (global.clipboardData) {
                    this.getRange().execCommand('paste', global.clipboardData.getData("text"));
                } else {
                    alert('Browser does not support paste');
                };
            },
            'cleardoc': function(val) {
                this.setValue('');
            },
            'print': function(val) {
                var val = val || this.getValue();
                this.getRange().execCommand('print', val);
            }
        };

    _.each(presets, function(index, cmd) {
        _.form.Editor.regCommand(cmd, function(val) {
            this.getRange().execCommand(cmd, val);
            this.setRange();
        });
    });

    _.each(customs, function(cmd, handler) {
        _.form.Editor.regCommand(cmd, handler);
    });

    _.extend(_.form.Editor, {
        regChecking: function(check, handler) {
            checks[check] = handler;
        }
    });
});