System.DeclareModel('BookmarkGroup', (YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Memowall = Launcher.Memowall,
        _ = System.Pandora;

    var Bookmark = System.ModuleSeeds.models.BookmarkModel(YangRAM, declare, global, undefined),
        buildGroupTitle = (name) => {
            return '<linkgroupname>' + name + '</linkgroupname>';
        };

    System.ModuleSeeds.models.BookmarkModel = undefined;

    return declare({
        _init(data) {
            this.name = data.name;
            this.build().render(data);
        },
        build() {
            this.Element = YangRAM.create('group', Memowall.MarkArea, {
                name: this.name
            });
            return this;
        },
        render(data) {
            var uid,
                html = buildGroupTitle(this.name) + '<list>';
            _.each(data.links, (i, linkdata) => {
                html += new Bookmark(linkdata).ToHTML();
            });
            html += '</list>';
            this.Element.innerHTML = html;
            return this;
        }
    });
});