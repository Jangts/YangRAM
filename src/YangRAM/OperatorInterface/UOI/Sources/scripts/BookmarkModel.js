System.DeclareModel('BookmarkModel', (YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Bookmark = Launcher.Bookmark,
        Memowall = Launcher.Memowall,
        _ = System.Pandora;

    var Groups = {},
        Links = {},
        LinKTemplate = '<item>' +
        '<bookmark appid="{@appid}" href="{@href}" menu="bookmark" type="{@type}" style="background-color: {@bgColor};">' +
        '<icon style="background-image: url({@source});"></icon>' +
        '<title>{@name}</title><vision title="{@desc}"></vision>' +
        '</bookmark></item>',
        InfoTemplate = '<item>' +
        '<bookmark appid="{@appid}" href="{@href}" menu="bookmark" type="{@type}" style="background-color: {@bgColor};">' +
        '<icon style="background-image: url({@source});"></icon>' +
        '<title>{@name}</title><desc>{@desc}</desc><vision></vision>' +
        '</bookmark></item>',
        RSRCTemplate = '<item>' +
        '<bookmark appid="{@appid}" href="{@href}" menu="bookmark" type="{@type}" style="background-color: {@bgColor};">' +
        '<iframe src="{@source}" scrolling="no"></iframe>' +
        '<vision title="{@desc}"></vision>' +
        '</bookmark></item>',
        ColorIndex = -1,
        linkColors = [
            "DarkMagenta",
            "DarkSlateBlue",
            "DimGray",
            "Indigo",
            "OrangeRed",
            "Tomato",
            "Darkorange",
            "Crimson"
        ],
        buildGroupTitle = (name) => {
            return '<linkgroupname>' + name + '</linkgroupname>';
        };

    return declare({
        Element: null,
        group: null,
        appid: 0,
        source: '',
        name: 'New Link',
        _init(uid, data) {
            this.uid = uid;
            this.group = data.group;
            this.appid = data.appid;
            this.type = data.type;
            this.sourceToken = data.src;
            this.name = data.name;
            this.desc = data.description;
            this.href = data.href;
            this.type = data.type;
        },
        getSource() {
            switch (this.type) {
                case 'profile':
                case 'friend':
                    this.source = YangRAM.RequestDIR + 'users/account/get-user-avatar/' + this.sourceToken + '/';
                    break;
                case 'image':
                    this.source = YangRAM.RequestDIR + this.appid + '/wallwidgets/dynamic-image/';
                    break;
                case 'msgs':
                    this.source = YangRAM.RequestDIR + this.appid + '/wallwidgets/messages/';
                    break;
                case 'embed':
                    this.source = YangRAM.RequestDIR + this.appid + '/wallwidgets/embed/' + this.sourceToken + '/';
                    break;
                default:
                    //console.log(this.appid, this.sourceToken);
                    if (this.sourceToken) {
                        this.source = YangRAM.RequestDIR + 'uoi/apps/icons/' + this.appid + '/links/' + this.sourceToken + '';
                    } else {
                        this.source = YangRAM.RequestDIR + 'uoi/apps/icons/' + this.appid + '/80/';
                    }
                    break;
            }
            return this;
        },
        Colorful() {
            var index = Math.round(Math.random() * 7);
            while (index === ColorIndex) {
                index = Math.round(Math.random() * 7);
            }
            this.bgColor = linkColors[index];
            ColorIndex = index;
            return this;
        },
        ToHTML() {
            this.getSource().Colorful();
            switch (this.type) {
                case 'info':
                    return new _.dom.Template(InfoTemplate, this).echo();
                    break;
                case 'msgs':
                case 'embed':
                    return new _.dom.Template(RSRCTemplate, this).echo();
                    break;
                default:
                    return new _.dom.Template(LinKTemplate, this).echo();
            }
        }
    });
});