System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Bookmark = Launcher.Bookmark,
        Memowall = Launcher.Memowall,
        Modifier = Launcher.Modifier,
        _ = System.Pandora;


    var Bookmarks, getData = () => {
            var data = [];
            YangRAM.$('Launcher content group').each(function() {
                var groupname = YangRAM.attr(this, 'name');
                var group = [];
                YangRAM.$('bookmark', this).each(function() {
                    group.push({
                        appid: YangRAM.attr(this, 'appid'),
                        title: YangRAM.$('title', this).html(),
                        href: YangRAM.attr(this, 'href'),
                        view: YangRAM.attr(this, 'view'),
                        icon: YangRAM.$('icon', this).css('background-image'),
                    });
                });
                data[groupname] = group;
            });
            return data;
        },
        buildGroups = () => {
            var html = '';
            for (var g in Launcher.Data) {
                html += '<option value="' + g + '">' + g + '</option>';
            }
            return html;
        },
        buildItems = (g) => {
            var html = '';
            if (Launcher.Data[g]) {
                for (var i in Launcher.Data[g]) {
                    html += '<option value="' + i + '">' + Launcher.Data[g][i]['title'] + '</option>';
                }
            }
            return html;
        },
        buildForm = (g, i) => {
            var html = '';
            var sysAppnames = {
                '1': 'Trash Can',
                '2': 'Content Explorer',
                '3': 'Settings'
            };
            if (Launcher.Data[g] && Launcher.Data[g][i]) {
                var appid = Launcher.Data[g][i]['appid'];
                var appname = sysAppnames[appid] || dataApps[appid] && dataApps[appid]['name'];
                if (appname) {
                    var icon = Launcher.Data[g][i]['icon'];
                    var title = Launcher.Data[g][i]['title'];
                    var href = Launcher.Data[g][i]['href'];
                    var view = {
                        Center: Launcher.Data[g][i]['view'] == 'Center' ? 'selected' : '',
                        Cover: Launcher.Data[g][i]['view'] == 'Cover' ? 'selected' : '',
                    };
                    html += '<el class="icon" style="background-image: ' + icon + ';"></el>';
                    html += '<input name="title" value="' + title + '" />';
                    html += '<line></line>';
                    html += '<el class="label">App</el>';
                    html += '<el class="appname">' + appname + '</el>';
                    html += '<el class="label">Target</el>';
                    html += '<input name="href" value="' + href + '" />';
                    html += '<el class="label">Group</el>';
                    html += '<input name="group" value="' + g + '" />';
                    html += '<el class="label">View Mode </el>';
                    html += '<select name="view">';
                    for (var i in view) {
                        html += '<option value="' + i + '" ' + view[i] + '>' + i + '</option>';
                    }
                    html += '</select>';
                    html += '<click class="change">Confirm</click>';
                }
            }
            return html;
        };

    _.extend(Modifier, true, {
        name: Runtime.locales.LAUNCHER.MGR.APPNAME,
        Main: YangRAM.create('vision', false, {
            innerHTML: '<el class="choose">Choose a Link Item</el>'
        }),

        build(BookmarkGroupModel) {
            Bookmarks = BookmarkGroupModel;
            return this;
            this.Groups = YangRAM.create('select', this.main, {
                name: 'groups',
                innerHTML: buildGroups()
            });
            this.Items = YangRAM.create('select', this.main, {
                name: 'items',
                innerHTML: buildItems(this.Groups.value)
            });
            this.button = YangRAM.create('click', this.main, {
                className: 'complete',
                innerHTML: 'Completed'
            });
            this.Editor = YangRAM.create('form', this.main, {
                innerHTML: buildForm(this.Groups.value, this.Items.value)
            });
            this.document.appendChild(this.main);
            this.listenEvents();
            return this;
        },
        listenEvents() {
            var that = this;
            this.Groups.onchange = function() {
                that.Items.innerHTML = buildItems(this.value);
                that.Editor.innerHTML = buildForm(this.value, 0);
            }
            this.Items.onchange = function() {
                that.Editor.innerHTML = buildForm(that.Groups.value, this.value);
            }
            return this;
        },
    });
});