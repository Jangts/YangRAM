/*!
 * Interblocks Framework Source Code
 *
 * class forms/Editor
 * 
 * Date: 2015-09-04
 */
;
iBlock([
    '$_/form/Editor/style.css',
    '$_/util/bool.xtd',
    '$_/dom/',
    '$_/dom/Events.Cls',
    '$_/data/',
    '$_/form/Editor/parameters.tmp',
    '$_/form/Editor/events.tmp',
    '$_/form/Editor/checks.tmp'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console;

    var Editors = {},
        conmands = {},
        creaters = {},
        toolbarTypes = {};

    var parameters = cache.read(new _.Identifier('IBK_EDITOR_PARAMS').toString()),
        dialogs = cache.read(new _.Identifier('IBK_EDITOR_DIALOGS').toString()),
        checks = cache.read(new _.Identifier('IBK_EDITOR_CHECKS').toString()),
        events = cache.read(new _.Identifier('IBK_EDITOR_EVENTS').toString()),

        tooltypes = {
            '': 'empty',
            '|': 'separator',
            '/': 'linebreak'
        },
        statusTypes = {
            fontstatus: [
                '<lable>size: </lable><input type="text" class="ic editor-fsize-input" data-name="fontsize" value="14px">',
                '<lable>color: </lable><input type="text" class="ic editor-color-input" data-name="fontcolor" value="#000000">'
            ],
            tablestatus: [
                '<lable>width</lable><input type="text" class="ic editor-tablewidth-input" data-name="tablewidth" value="1">',
                '<lable>rows: </lable><input type="text" class="ic editor-rowslen" value="1" readonly>',
                '<i class="ic editor-table-adddata editor-table-addrow">Add Row</i>',
                '<lable>cols: </lable><input type="text" class="ic editor-colslen" value="1" readonly>',
                '<i class="ic editor-table-adddata editor-table-addcol">Add Column</i>',
                '<lable>border: </lable><input type="text" class="ic editor-border-input" data-name="tableborder" value="0">'
            ],
            imagestatus: [
                '<lable>width</lable><input type="text" class="ic editor-imgwidth-input" data-name="imgwidth" value="1">',
                '<lable>height</lable><input type="text" class="ic editor-imgheight-input" data-name="imgheight" value="1">',
                '<lable>border:</lable><input type="text" class="ic editor-border-input" data-name="imgborder" value="0">',
                '<i class="ic editor-imgfloat" data-float="none">No Float</i><i class="ic editor-imgfloat" data-float="left">Pull Left</i><i class="ic editor-imgfloat" data-float="right">Pull Right</i>'
            ]
        },
        statusHTML =
        '<div class="ic editor-fontstatus" title="Font Style"><section>' +
        statusTypes.fontstatus.join('</section><section>') +
        '</section></div><div class="ic editor-tablestatus" title="Table Style"><section>' +
        statusTypes.tablestatus.join('</section><section>') +
        '</section></div><div class="ic editor-imagestatus" title="Image Style"><section>' +
        statusTypes.imagestatus.join('</section><section>') +
        '</section></div>';

    var creater = {
        empty: function() {
            return '';
        },
        separator: function() {
            return '<div class="ic editor-tool separator" title="separator"></div>';
        },
        linebreak: function() {
            return '<div class="ic editor-tool linebreak" title="linebreak"></div>';
        },
        optionalitem: function(tool) {
            var html = '<div class="ic editor-tool ' + tool + '" data-ib-cmds="' + tool + '" title="' + tool + '"><i class="ic editor-icon"></i>';
            html += creaters[tool].call(this);
            html += '</div>';
            return html;
        },
        dialogitem: function(tool) {
            var html = '<div class="ic editor-tool ' + tool + '" data-ib-dialog="' + tool + '" title="' + tool + '"><i class="ic editor-icon"></i>';
            html += creaters[tool].call(this);
            html += '</div>';
            return html;
        },
        defaultitem: function(tool) {
            return '<div class="ic editor-tool ' + tool + '" data-ib-cmd="' + tool + '" title="' + tool + '"><i class="ic editor-icon"></i></div>';
        }
    };


    //Define NameSpace 'form'
    _('form');

    //Declare Class 'form.Editor'
    /**
     * forms inspection and submission and ect.
     * @class 'Editor'
     * @constructor
     * @param {String, Object<HTMLElement> }
     */

    declare('form.Editor', {
        textarea: null,
        toolbar: null,
        editarea: null,
        codearea: null,
        range: null,
        isRich: 1,
        attachment_type: null,
        upload_maxsize: 1024 * 1024 * 20,
        transfer: null,
        _init: function(textarea, settings) {
            if (_.util.bool.isEl(textarea)) {
                this.textarea = textarea;
                settings = settings || {};
                var width = settings.width || textarea.offsetWidth - 2;
                var height = settings.height || textarea.offsetHeight - 2;
                this.commonNode = _.dom.create('div', textarea.parentNode, {
                    className: 'ic editor editor-' + (settings.themeType || 'default'),
                    style: {
                        'width': width,
                        'min-height': height,
                        'border-color': (settings.border && settings.border.color) || '#CCCCCC',
                        'border-style': (settings.border && settings.border.style) || 'solid',
                        'border-width': (settings.border && settings.border.width) || '1px'
                    }
                });
                this.options = {};
                for (var i in settings) {
                    this.options[i] = settings[i];
                }
                if (settings.uploader) {
                    this.upload_maxsize = settings.uploader.maxsize;
                    this.attachment_type = settings.uploader.sfixs;
                    this.transfer = settings.uploader.transfer;
                }
                var id = 'editor' + ((Date.parse(new Date()) * 1000 + Math.floor(Math.random() * 1000000000000000)));
                _.dom.setAttr(this.commonNode, 'data-editor-id', id);
                Editors[id] = this;
            }
        },
        execCommand: function(cmd, val) {
            cmd = cmd.toLowerCase();
            if (conmands[cmd]) {
                conmands[cmd].call(this, val);
            }
            return this;
        },
        setValue: function(value) {
            this.editarea.innerHTML = this.textarea.value = this.codearea.value = value;
            return this.setRange();
        },
        getValue: function() {
            if (this.isRich) {
                this.codearea.value = this.editarea.innerHTML;
            } else {
                if (this.editarea.innerHTML != this.codearea.value) {
                    this.editarea.innerHTML = this.codearea.value;
                }
            }
            this.textarea.value = this.codearea.value;
            return this.codearea.value;
        },
        hideExtTools: function() {
            _.each(_.query('.ic.editor-tool[data-ib-dialog], .ic.editor-tool[data-ib-cmds]', this.toolbar), function(i, el) {
                _.dom.toggleClass(this, 'active', false);
            });
            return this;
        },
        showDialog: function(dialog) {
            this.hideExtTools();
            if (dialog) {
                var button = arguments[1] || _.query('.ic.editor-tool[data-ib-dialog=' + dialog + ']', this.toolbar)[0];
                _.dom.toggleClass(button, 'active');
            };
            return this;
        },
        showPick: function(cmds) {
            this.hideExtTools();
            if (cmds) {
                var height = this.isRich ? _.dom.getHeight(this.editarea, 'box') : _.dom.getHeight(this.codearea, 'box');
                var button = arguments[1] || _.query('.ic.editor-tool[data-ib-cmds=' + cmds + ']', this.toolbar)[0];
                _.dom.toggleClass(button, 'active');
                var list = _.query('.ic.editor-pick', button)[0];
                _.dom.setStyle(list, 'max-height', height - 15);
            };
            return this;
        },
        listen: function() {
            var editor = this,
                listeners = {
                    toolbar: new _.dom.Events(this.toolbar),
                    statusbar: new _.dom.Events(this.statusbar),
                    workspace: new _.dom.Events(this.editarea)
                };
            _.each(listeners, function(name, listener) {
                _.each(events[name], function(eventType, handler) {
                    if (_.util.bool.isFn(handler)) {
                        listener.push(eventType, null, editor, handler);
                    } else if (_.util.bool.isObj(handler)) {
                        _.each(handler, function(selector, fn) {
                            listener.push(eventType, selector, editor, fn);
                        });
                    }
                })
            });
        },
        render: function(toolbar) {
            if (_.util.bool.isObj(toolbar)) {
                if (_.util.bool.isEl(toolbar)) {
                    toolbar.innerHTML = toolbar.innerHTML;
                    this.toolbar = toolbar;
                }
            } else {
                if (!this.options.toolbarItems) {
                    this.options.toolbarItems = toolbarTypes[this.options.toolbarType] || toolbarTypes['default'];
                }
                var html = '';
                for (var i = 0; i < this.options.toolbarItems.length; i++) {
                    //console.log(this.options.toolbarItems[i]);
                    html += creater[tooltypes[this.options.toolbarItems[i]]].call(this, this.options.toolbarItems[i]);
                }
                html += '<div class="ic editor-clear"></div>';
                this.toolbar = _.dom.create('div', this.commonNode, {
                    className: 'ic editor-toolbar',
                    innerHTML: html
                });

            }
            var style = _.dom.getStyle(this.textarea);
            var width = parseInt(style.width) - 12;
            var height = parseInt(style.height) - parseInt(_.dom.getStyle(this.toolbar, 'height')) - 12;
            this.editarea = _.dom.create('div', this.commonNode, {
                className: 'ic editor-editerea',
                placeholder: _.dom.getAttr(this.textarea, 'placeholder'),
                contenteditable: 'true',
                spellcheck: 'true',
                talistenex: 1,
                style: {
                    'width': width,
                    'height': height,
                    'padding': '5px',
                    'outline': 'none'
                },
                innerHTML: this.textarea.value
            });
            this.codearea = _.dom.create('textarea', this.commonNode, {
                className: 'ic editor-codearea',
                contenteditable: 'true',
                spellcheck: 'true',
                talistenex: 1,
                style: {
                    'width': width,
                    'height': height,
                    'display': 'none',
                    'padding': '5px',
                    'outline': 'none'
                },
                value: this.textarea.value
            });
            this.loadmask = _.dom.create('div', this.commonNode, {
                className: 'ic editor-loadmask',
                innerHTML: '<div class="ic editor-spinner"><div class="ic editor-rect1"></div><div class="ic editor-rect2"></div><div class="ic editor-rect3"></div><div class="ic editor-rect4"></div><div class="ic editor-rect5"></div></div>'
            });

            this.statusbar = _.dom.create('div', this.commonNode, {
                className: 'ic editor-statusbar',
                innerHTML: statusHTML
            });
            _.dom.setStyle(this.textarea, {
                display: 'none'
            });
            return this;
            //checkPlaceHolder.call(this);
        },
        setRange: function(_range) {
            //console.log(_range);
            _.each(Editors, function(i, editor) {
                //editor._range = editor.range;
                editor.range = null;
            });
            range = new _.form.Range(_range);
            if (range.isBelongTo(this.editarea)) {
                this._range = this.range = range;
            } else {
                this.getRange();
            }
            _.each(checks, function(check, handler) {
                handler.call(this);
            }, this);
            //console.log(this.range);
            return this;
        },
        getRange: function() {
            range = this.range || this._range;
            //console.log(range);
            if (range && range.isBelongTo(this.editarea)) {
                if (range.originRange.select) {
                    range.originRange.select();
                    this._range = this.range = range;
                } else {
                    this.setRange(range);
                }
            } else {
                var range = new _.form.Range();
                this._range = this.range = range.selectElememt(this.editarea);
            }
            //console.log(this.range);
            return this.range;
        }
    });

    _.extend(_.form, true, {
        careatEditor: function(elem, settings) {
            var editor = new _.form.Editor(elem, settings);
            editor.render().listen();
            return editor;
        },
        careatEditors: function(selector, settings) {
            var editors = [];
            _.each(_.query(selector), function(i, el) {
                var editor = _.form.careatEditor(el, settings);
                editors.push(editor);
            });
            return editors;
        },
        getEditorById: function(id) {
            return id && Editors[id];
        }
    });

    _.extend(_.form.Editor, {
        extends: function(object, rewrite) {
            _.extend(_.form.Editor.prototype, rewrite, object);
        },
        regCommand: function(cmd, handler) {
            if (conmands[cmd] === undefined) {
                conmands[cmd] = handler;
                tooltypes[cmd] = 'defaultitem';
            }
        },
        regCreater: function(cmd, handler, optional) {
            if (creaters[cmd] === undefined) {
                if (_.util.bool.isFn(handler)) {
                    creaters[cmd] = handler;
                    if (optional) {
                        tooltypes[cmd] = 'optionalitem';
                    }
                } else if (_.util.bool.isStr(handler) && creater[handler]) {
                    tooltypes[cmd] = handler;
                }
            }
        },
        regDialog: function(cmd, handler) {
            if (dialogs[cmd] === undefined) {
                dialogs[cmd] = handler;
                tooltypes[cmd] = 'dialogitem';
            }
        },
        regToolbarType: function(type, items) {
            if (toolbarTypes[type] === undefined) {
                toolbarTypes[type] = items;
            }
        }
    });

    //console.log(dialogs);
});