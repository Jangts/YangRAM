/*!
 * Interblocks Framework Source Code
 *
 * class data.Component
 *
 * Date 2017-04-06
 */
;
iBlock([
    '$_/util/arr.xtd',
    '$_/util/obj.xtd',
    '$_/data/Storage.Cls',
    '$_/data/jsonsql.xtd'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console,
        location = global.location,
        Storage = _.data.Storage,
        jsonsql = _.data.jsonsql;

    var infs = {},
        tables = {},
        metable = new Storage('pandora.data.Table'),
        create = function(tablename, fields, primarykey, constraints) {
            var defaultStorageName = 'pandora.data.Table.' + tablename;
            return {
                ai: 0,
                length: 0,
                name: tablename,
                fields: fields,
                pk: primarykey,
                constraints: constraints,
                storages: [defaultStorageName]
            }
        },
        partlen = 1000 * 1000 * 5,
        update = function(tablename) {
            var storageName,
                mateinfs = infs[tablename],
                json = global.JSON.stringify(tables[tablename]),
                parts = json.length / partlen,
                i = 0,
                storages = [];
            for (i; i < parts; i++) {
                storageName = 'pandora.data.Table.' + tablename + (i ? i : '');
                storages.push(storageName);
                new Storage(storageName).set('data', json.substr(partlen * i, partlen));
            }
            mateinfs.length = _.util.obj.length(tables[tablename]);
            mateinfs.storages = storages;
            metable.set(tablename, mateinfs);
        },
        check = function(value, constraint) {
            switch (constraint.type) {
                case 'boolean':
                    return _.util.arr.has([true, false, 1, 0], value);

                case 'object':
                    return (typeof value === 'object');

                case 'string':
                    if (typeof value === 'string') {
                        if (constraint.length) {
                            if (value.length > constraint.length) {
                                return false;
                            }
                        }
                        if (constraint.pattern) {
                            var patt = new RegExp(constraint.pattern);
                            console.log(patt, value, patt.test(value));
                            return patt.test(value);
                        }
                        return true;
                    }

                    return false;

                case 'number':
                    if (typeof value === 'number') {
                        if (constraint.max) {
                            if (value.length > constraint.length) {
                                return false;
                            }
                        }
                        if (constraint.min) {
                            if (value.length < constraint.min) {
                                return false;
                            }
                        }
                        return true;
                    }
                    return false;

                default:
                    return true;
            }
        },
        assign = function(mateinfs, data, _data) {
            _.each(mateinfs.fields, function(fieldname, value) {
                if ((fieldname != mateinfs.pk) && data.hasOwnProperty(fieldname)) {
                    if (mateinfs.constraints && mateinfs.constraints[fieldname]) {
                        if (check(data[fieldname], mateinfs.constraints[fieldname])) {
                            _data[fieldname] = data[fieldname];
                        } else {
                            if (!_data.hasOwnProperty(fieldname)) {
                                _data[fieldname] = value;
                            }
                        }
                    } else {
                        _data[fieldname] = data[fieldname];
                    }
                } else {
                    if (!_data.hasOwnProperty(fieldname)) {
                        _data[fieldname] = value;
                    }
                }
            });
            return _data;
        };

    declare('data.Table', {
        _init: function(tablename, fields, primarykey, constraints) {
            if (_.namingExpr.test(tablename)) {
                this.tablename = tablename;
                console.log(tables[tablename]);
                if (!tables[tablename]) {
                    var mateinfs = metable.get(tablename);
                    console.log(mateinfs);
                    if (!mateinfs) {
                        if (fields) {
                            mateinfs = create(tablename, fields, primarykey, constraints);
                            metable.set(tablename, mateinfs);
                        } else {
                            mateinfs = create(tablename, { 'id': 0 }, 'id', {});
                            metable.set(tablename, mateinfs);
                        }
                    }
                    var json = '';
                    _.each(mateinfs.storages, function(i, storageName) {
                        json += new Storage(storageName).get('data');
                    });
                    // console.log(json);
                    infs[tablename] = mateinfs;
                    try {
                        tables[tablename] = global.JSON.parse(json);
                    } catch (e) {
                        tables[tablename] = {};
                    }
                }
            } else {
                _.error('Error Tablename');
            }
        },
        add: function(fieldname, value, constraint) {
            var tablename = this.tablename,
                mateinfs = infs[tablename];
            if (!mateinfs.fields.hasOwnProperty(fieldname)) {
                mateinfs.fields[fieldname] = value = value || '';
                if (constraint && constraint.type) {
                    mateinfs.constraints[fieldname] = constraint;
                }
                _.each(tables[tablename], function(id, row) {
                    row[fieldname] = value;
                });
                update(tablename);
            }
            return this;
        },
        drop: function(fieldname) {
            var tablename = this.tablename,
                mateinfs = infs[tablename];
            if (fieldname) {
                if (mateinfs.fields.hasOwnProperty(fieldname)) {
                    delete mateinfs.fields[fieldname];
                    delete mateinfs.constraints[fieldname];
                    _.each(tables[tablename], function(id, row) {
                        delete row[fieldname];
                    });
                    update(this.tablename);
                }
                return this;
            }
            _.each(mateinfs.storages, function(i, storageName) {
                new Storage(storageName).clear(true);
            });
            metable.set(tablename);
            delete infs[tablename];
            delete tables[tablename];
            return null;
        },
        alter: function(fieldname, newname, value) {
            var tablename = this.tablename,
                mateinfs = infs[tablename];
            if (fieldname) {
                if (mateinfs.fields.hasOwnProperty(fieldname)) {
                    mateinfs.fields[newname] = value === undefined ? mateinfs.fields[fieldname] : value;
                    delete mateinfs.fields[fieldname];
                    mateinfs.constraints[newname] = mateinfs.constraints[fieldname];
                    delete mateinfs.constraints[fieldname];
                    _.each(tables[tablename], function(id, row) {
                        row[newname] = row[fieldname];
                        delete row[fieldname];
                    });
                    update(tablename);
                }
                return this;
            }
            return this;
        },
        insert: function(data) {
            var tablename = this.tablename,
                mateinfs = infs[tablename];
            _.each(arguments, function(i, data) {
                data = assign(mateinfs, data, {});
                data[mateinfs.pk] = ++mateinfs.ai;
                tables[tablename][mateinfs.ai] = data;
            });
            update(tablename);
            return mateinfs.ai;
        },
        update: function(id, data) {
            var tablename = this.tablename,
                mateinfs = infs[tablename];
            if (id && tables[tablename].hasOwnProperty(id) && data) {
                tables[tablename][id] = assign(mateinfs, data, tables[tablename][id]);
                update(tablename);
            }
            return this;
        },
        select: function(id) {
            var tablename = this.tablename;
            if (id) {
                rs = [];
                _.each(arguments, function(i, id) {
                    if (tables[tablename][id]) {
                        rs.push(tables[tablename][id]);
                    }
                });
                return rs;
            }
            return _.util.obj.toArray(tables[tablename]);
        },
        delete: function(id) {
            var tablename = this.tablename;
            if (id) {
                _.each(arguments, function(i, id) {
                    delete tables[tablename][id]
                });
                update(tablename);
            }
            return this;
        },
        fields: function() {
            var fields = {},
                mateinfs = infs[this.tablename];
            _.each(mateinfs.fields, function(fieldname, value) {
                fields[fieldname] = {
                    default: value,
                    constraint: mateinfs.constraints && mateinfs.constraints[fieldname],
                    isPKey: fieldname === mateinfs.pk
                };
            });
            return fields;
        },
        createtable: function(width, border) {
            if (width) {
                _width = ' width="' + width + '"';
            } else {
                _width = '';
            }
            if (border) {
                _border = ' border="' + border + '"';
            } else {
                _border = '';
            }
            var mateinfs = infs[this.tablename],
                rows = tables[this.tablename],
                html = '<table ' + _width + _border + '><tbody><tr><th>' + mateinfs.pk + '</th>';
            _.each(mateinfs.fields, function(fieldname) {
                if (fieldname != mateinfs.pk) {
                    html += '<th>' + fieldname + '</th>';
                }
            });
            _.each(rows, function(id, row) {
                html += '</tr><tr><th>' + id + '</th>';
                _.each(row, function(fieldname, value) {
                    if (fieldname != mateinfs.pk) {
                        html += '<td>' + value + '</td>';
                    }
                });
            });
            html += '</tr></tbody></table>';
            return html;
        }
    });

    _('data.Table', {
        exec: function(str) {
            a = jsonsql.query(str, tables);
            b = str.match(/(select|delete|update|insert)/);
            console.log(str, a);
        }
    });

    global.console.log(tables, infs);
});