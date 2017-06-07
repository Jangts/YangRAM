System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Kalendar = System.Kalendar,
        _ = System.Pandora;

    var handlers = {
        'kalendar dates list': {
            'click' (event) {
                listEventsClick = setTimeout(() => {
                    if (Kalendar.DdlClick) {
                        Kalendar.DdlClick--;
                    } else {
                        if (this == Kalendar.Editing) {
                            Kalendar.SetType('date').SelectDates().SaveEvents(this);
                            System.Workspace.Launcher.ARL.PassiveMode && System.Workspace.Launcher.ARL.launch();
                            Kalendar.SetCurr();
                        } else {
                            YangRAM.API.APP.launch('I4PLAZA');
                            System.Workspace.Launcher.Memowall.sleep().PassiveMode = true;
                            Kalendar.SetType('edit').SelectEvents().SaveEvents(Kalendar.Editing).SetCurr(this).EditEvents();
                        }
                    }
                }, 500);
            }
        },
        'kalendar dates el[name=prev-month]': {
            'click' (event) {
                Kalendar.Month--;
                Kalendar.request();
            }
        },
        'kalendar dates el[name=next-month]': {
            'click' (event) {
                Kalendar.Month++;
                Kalendar.request();
            }
        },
        'kalendar dates el[name=curr-month]': {
            'dblclick' (event) {
                Kalendar.Month = System.HiBar.Timer.month();
                Kalendar.request();
            }
        },
        'kalendar dates': {
            'click' (event) {
                Kalendar.SelectDates();
            }
        },
        'kalendar content': {
            'click' (event) {
                Kalendar.SelectEvents();
            }
        },
        'kalendar content switch item': {
            'click' (event) {
                YangRAM.$('kalendar content sections>*, kalendar content switch item').attr('state', 'off');
                YangRAM.attr(this, 'state', 'on');
                var owner = YangRAM.$(this).attr('owner');
                if (Kalendars.indexOf(owner) >= 0) {
                    var Kalendarname = owner.replace(/^\w/, (s) => s.toUpperCase());
                    YangRAM.attr(Kalendar[Kalendarname], 'state', 'on');
                }
                Kalendar.scrollBAR.resize();
            }
        }
    };

    var evisionStyle = () => {
        var css = '';
        var styles = {
            title: 'Title : ',
            begin: 'Begin Time : ',
            end: 'End Time : ',
            invitee: 'Invitee : ',
            notification: 'Notification : ',
            url: 'URL : ',
            remark: 'Remark : '
        }
        for (var i in styles) {
            css += '\n\rkalendar content sections event ' + i;
            css += ':before { content: "' + styles[i] + '";}';
        }
        return css;
    };

    var eSwitchItems = {
        private: ['Mine', 'on'],
        public: ['Public', 'off'],
        captain: ['Captain\'s', 'off'],
        festival: ['Festival&nbsp;&amp;&nbsp;Holiday', 'off']
    }

    var eSwitchItem = () => {
        var html = '<switch>';
        for (var i in eSwitchItems) {
            html += '<item state="' + eSwitchItems[i][1] + '" owner="' + i + '">' + eSwitchItems[i][0] + '</item>';
        }
        html += '</switch>';
        return html;
    };

    var Kalendars = ['private', 'public', 'captain', 'festival'];

    var eSwitchvision = (i, sections) => YangRAM.create('vision', sections, {
        owner: i,
        state: eSwitchItems[i][1]
    });

    _.extend(Kalendar, true, {
        name: Runtime.locales.KALENDAR.APPNAME,
        Year: System.HiBar.Timer.year(),
        Month: System.HiBar.Timer.month(),
        Editing: undefined,
        DdlClick: 0,
        build() {
            YangRAM.create('style', this.document, {
                innerHTML: evisionStyle()
            });
            YangRAM.create('scrollbar', this.document, {
                type: 'vert',
                innerHTML: '<rail></rail><scrolldragger></scrolldragger>'
            });
            this.Dates = YangRAM.create('dates', this.document);
            this.vision = YangRAM.create('content', this.document, {
                innerHTML: eSwitchItem()
            });
            var sections = YangRAM.create('sections', this.vision);
            for (var i in Kalendars) {
                var Kalendarname = Kalendars[i].replace(/^\w/, (s) => s.toUpperCase());
                this[Kalendarname] = eSwitchvision(Kalendars[i], sections);
            }
            //TimePicker.build();
            this.off().resize().listenEvents();
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(this.document);
        },
        launch() {
            YangRAM.API.APP.sleep('SYSTEM-MODULES').attr(System.HiBar.Clock, 'type', 'kalendar').attr(this.Dates, 'state', 'on');
            return this.on();
        },
        load(callback) {
            var date = this.Year + '/' + this.Month;
            YangRAM.get({
                url: YangRAM.RequestDIR + 'uoi/account/events/' + date + '/?lang=' + Runtime.locales.CODE,
                done: (txt) => {
                    //console.log(txt);
                    this.Dates.innerHTML = System.TrimHTML(txt);
                    _.util.bool.isFn(callback) && callback();
                }
            });
            return this;
        },
        sleep() {
            YangRAM.attr(System.HiBar.Clock, 'type', 'start');
            System.Workspace.Launcher.ARL.PassiveMode && System.Workspace.Launcher.ARL.launch();
            return this.SaveEvents(this.Editing).SetType('date').SelectDates().off().SetCurr().resize();
        },
        SetType(type) {
            return this.attr('type', type).resize();
        },
        SelectEvents() {
            YangRAM.attr(this.vision, 'selected', '');
            return this;
        },
        SelectDates() {
            _.dom.removeAttr(this.vision, 'selected');
            return this;
        },
        SetCurr(editing) {
            this.Editing = editing;
            YangRAM.$('kalendar dates list[actived]').removeAttr('actived');
            if (editing) {
                YangRAM.attr(editing, 'actived', '');
            };
            return this;
        },
        EditEvents() {
            var day = this.Editing.innerHTML;
            var events = [];
            YangRAM.$('kalendar dates events').each(function(i, e) {
                var owner = YangRAM.$(this).attr('owner');
                var html = YangRAM.$('date[day="' + day + '"]', this).html();
                events[owner] = html;
            });
            this.scrollBAR.toTop();
            this.LoadEvents(events).resize();
            return this;
        },
        LoadEvents(events) {
            var that = this;
            YangRAM.$('kalendar content vision').each(function(i, e) {
                var owner = YangRAM.$(this).attr('owner');
                if (events[owner]) {
                    YangRAM.$(this).html(events[owner]);
                    if (owner == 'private' || owner == 'public') {
                        that.AddSpcl(this);
                    }
                } else {
                    if (owner == 'private' || owner == 'public') {
                        YangRAM.$(this).html('<el class="noevent newevent">No Events(Click To create Events)</el>');
                    } else {
                        YangRAM.$(this).html('<el class="noevent">No Events</el>');
                    }
                }
            });
            return this;
        },
        createEvent(node) {
            this.RemoveSpcl(node);
            var html = '<title contenteditable="true"> </title>';
            html += '<begin>00:00</begin>';
            html += '<end>23:59</end>';
            html += '<url> </url>';
            html += '<remark> </remark>';
            YangRAM.create('event', node, {
                innerHTML: html
            });
            this.AddSpcl(node);
            this.scrollBAR.resize();
            return this;
        },
        RemoveSpcl(node) {
            var el = YangRAM.$('el', node)[0];
            if (el) {
                node.removeChild(el);
            };
            return this;
        },
        AddSpcl(node) {
            YangRAM.create('el', node, {
                className: 'newevent',
                innerHTML: 'Click To Add A New Event'
            });
            return this;
        },
        SaveEvents() {
            return this;
            TimePicker.off();
            if (this.Editing) {
                YangRAM.$('kalendar content [contentEditable]').removeAttr('contentEditable');
                this.Filter(this.Private);
                this.Filter(this.Public);
                this.SaveOwner('private');
                this.SaveOwner('public');
                this.resize();
            }

        },
        SaveOwner(owner) {
            var day = this.Editing.innerHTML;
            if (owner == 'private') {
                var events = _.str.trim(YangRAM.$(this.Private).html() || '');
            } else if (owner == 'public') {
                var events = _.str.trim(YangRAM.$(this.Public).html() || '');
            }
            var selector = 'kalendar dates events[owner=' + owner + ']';
            if (YangRAM.$(selector + ' date[day=' + day + ']')[0]) {
                if (YangRAM.$(selector + ' date[day=' + day + ']').html() != events || events == '') {
                    if (events == '') {
                        var node = YangRAM.$(selector)[0]
                        var date = YangRAM.$(selector + ' date[day=' + day + ']')[0];
                        node.removeChild(date);
                        _.dom.removeAttr(this.Editing, 'events')
                    } else {
                        YangRAM.$(selector + ' date[day=' + day + ']').html(events);
                    }
                    this.SaveKalendar(owner);
                }
            } else {
                if (events != '') {
                    YangRAM.create('date', YangRAM.$(selector)[0], {
                        day: day,
                        innerHTML: events
                    });
                    YangRAM.attr(this.Editing, 'events', '');
                    this.SaveKalendar(owner);
                }
            };
            return this;
        },
        Filter(node) {
            this.RemoveSpcl(node);
            YangRAM.$('event', node).each(function() {
                var title = YangRAM.$('title', this).html();
                title = title.replace(/&gt;/g, '>').replace(/&lt;/g, '<').replace(/<[^>]+>/g, '');
                title = _.str.trim(title);
                if (title == '') {
                    node.removeChild(this);
                } else {
                    YangRAM.$('title', this).html(title);
                }
            });
            return this;
        },
        SaveKalendar(owner) {
            var date = this.Year + '/' + this.Month;
            var data = new FormData();
            data.append(owner, (YangRAM.$('kalendar dates events[owner=' + owner + ']').html() || '').replace(/>\*<\//, '> </').replace(/(&nbsp;)+/, ' '));
            YangRAM.set({
                url: YangRAM.RequestDIR + 'uoi/account/events/' + date + '/?lang=' + Runtime.locales.CODE,
                data: data
            });
            return this;
        },
        onafterresize() {
            var height = this.Editing ? System.Height - 40 : 501;
            YangRAM.setStyle(this.document, {
                height: height
            });
        },
        listenEvents() {
            /*
            _.dom.events.remove(this.Private, 'click');
            _.dom.events.remove(this.Public, 'click');
            _.dom.events.add(this.Private, 'click', 'title, url, remark', null, handlers.Editable);
            _.dom.events.add(this.Public, 'click', 'title, url, remark', null, handlers.Editable);
            _.dom.events.add(this.Private, 'click', 'begin, end', null, handlers.TimePicker);
            _.dom.events.add(this.Public, 'click', 'begin, end', null, handlers.TimePicker);
            _.dom.events.add(this.Private, 'click', 'el.newevent', null, handlers.NewPrivate);
            _.dom.events.add(this.Public, 'click', 'el.newevent', null, handlers.NewPublic);
            */
            return this.__proto__.listenEvents.call(this, handlers);
        }
    });
});