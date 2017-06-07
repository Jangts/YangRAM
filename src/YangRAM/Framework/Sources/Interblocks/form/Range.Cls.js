/*!
 * Interblocks Framework Source Code
 *
 * class form.Range
 *
 * Date: 2017-04-06
 */
;
iBlock('$_/util/bool.xtd', function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console;

    var isGetSelection = window.getSelection ? true : false,
        getSelectionRange = function(selection) {
            if (selection.rangeCount > 0) {
                this.originRange = selection.getRangeAt(0);
                this.type = selection.type;
                this.collapsed = this.originRange.collapsed;
                this.commonNode = this.originRange.commonAncestorContainer;
                this.startNode = selection.anchorNode;
                this.startOffset = selection.anchorOffset;
                this.endNode = selection.focusNode;
                this.endOffset = selection.focusOffset;
                this.text = this.originRange.toString();
                var div = document.createElement('div')
                div.appendChild(this.originRange.cloneContents());
                this.html = div.innerHTML;
            } else {
                this.originRange = undefined;
                this.type = 'Caret';
                this.collapsed = true;
                this.commonNode = null;
                this.startNode = null;
                this.startOffset = 0;
                this.endNode = null;
                this.endOffset = 0;
                this.text = '';
                this.html = '';
            }
        },
        docSelectionRange = function() {
            this.originRange = document.selection.createRange();
            if (this.originRange.text == '') {
                this.type = 'Caret';
                this.collapsed = true;
            } else {
                this.type = 'Range';
                this.collapsed = false;
            }
            this.commonNode = this.originRange.parentElement()
            this.startNode = null;
            this.startOffset = 0;
            this.endNode = null;
            this.endOffset = 0;
            this.text = this.originRange.text;
            this.html = this.originRange.htmlText;
        };

    //Declare Class 'Form'
    /**
     * forms inspection and submission and ect.
     * @class 'Editor'
     * @constructor
     * @param {String, Object<HTMLElement> } 
     */
    declare('form.Range', {
        originRange: undefined,
        type: 'Caret',
        collapsed: true,
        commonNode: null,
        commonElem: null,
        startNode: null,
        startOffset: 0,
        endNode: null,
        endOffset: 0,
        text: '',
        html: '',
        _init: function(range) {
            if (isGetSelection) {
                var selection = window.getSelection();
                if (range && range.originRange) {
                    selection.removeAllRanges();
                    selection.addRange(range.originRange);
                }
                getSelectionRange.call(this, selection);
            } else {
                docSelectionRange.call(this);
            }
            this.commonElem = this.commonNode && (_.util.bool.isEl(this.commonNode) ? this.commonNode : this.commonNode.parentNode) || null;
        },
        isBelongTo: function(editElem) {
            return editElem && this.commonNode && _.dom.contain(editElem, this.commonNode)
        },
        selectElememt: function(editElem) {
            if (editElem && typeof editElem.focus == 'function') {
                editElem.focus();
                if (window.getSelection) {
                    getSelectionRange.call(this, window.getSelection())
                } else if (document.selection) {
                    docSelectionRange.call(this);
                }
                this.commonElem = editElem;
                return this;
            }
            return null;
        },
        collapse: function(toStart) {
            if (window.getSelection) {
                var selection = window.getSelection();
                if (toStart) {
                    selection.collapse(this.startNode, this.startOffset);
                } else {
                    selection.collapse(this.endNode, this.endOffset);
                }
                getSelectionRange.call(this, selection)
            } else if (this.originRange.select) {
                this.originRange.collapse(toStart)
                this.originRange.select();
            }
        },
        execCommand: function(cmd, val, isDialog) {
            //console.log(this);
            isDialog = isDialog || false;
            if (isGetSelection) {
                document.execCommand(cmd, isDialog, val);
            } else {
                this.originRange.execCommand(cmd, isDialog, val);
            }
        }
    });
});