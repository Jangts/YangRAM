System.DeclareModel('BookmarkGroup', (YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        //Bookmark = Launcher.Bookmark,
        Memowall = Launcher.Memowall,
        _ = System.Pandora;

    var Bookmark = System.ModuleSeeds.models.BookmarkModel(YangRAM, declare, global, undefined),
        groups = {},
        links = {},
        buildGroupTitle = (name) => {
            return '<linkgroupname>' + name + '</linkgroupname>';
        };

    System.ModuleSeeds.models.BookmarkModel = undefined;

    return declare({
        _init(data) {
            this.uid = new _.Identifier();
            this.name = data.name;
            groups[this.uid] = this;
            this.links = [];
            this.build().render(data);
            //console.log(this);
        },
        build() {
            this.Element = YangRAM.create('group', Memowall.MarkArea, {
                uid: this.uid,
                name: this.name,
                menu: 'bookmark-group'
            });
            return this;
        },
        render(data) {
            var uid,
                html = buildGroupTitle(this.name) + '<list>';
            _.each(data.links, (i, linkdata) => {
                uid = new _.Identifier();
                links[uid] = new Bookmark(uid, linkdata);
                this.links.push(uid);
                html += links[uid].ToHTML();
            });
            html += '</list>';
            this.Element.innerHTML = html;
            return this;
        }
        /*,
        render() {
            if (this.Element) {
                YangRAM
                    .attr(this.Element, 'uid', this.uid)
                    .attr(this.Element, 'appid', this.appid)
                    .attr(this.Element, 'href', this.href)
                    .attr(this.Element, 'view', this.ViewMode)
                    .setStyle(this.Element, { backgroundImage: this.icon });
                this.Element.innerHTML = this.title;
            } else {
                this.Element = YangRAM.create('bookmark', this.Group.Element, {
                    appid: this.appid,
                    href: this.href,
                    view: this.ViewMode,
                    style: {
                        backgroundImage: this.icon
                    },
                    html: this.title
                });
            }
            this.Group.ReRank();
            return this.Element;
        },
        moveTo(group, index) {
            if (group !== this.Group) {
                this.Group.Element.removeChild(this.Element);
                this.Group.ReRank();
                this.Group = group;
                this.index = index;
                this.Element = null;
                this.render();
            } else {
                this.Group.ReRank();
            }
            return this;

        }*/
    });
});