System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        TimePicker = System.TimePicker,
        _ = System.Pandora;

    var builders,
        ConfirmCallback = null,
        CurrentInputType = 'datetime',
        CurrentInputValue = '1970-01-01 00:00:00',
        CurrentFirstYear = 1931,
        CurrentValuesList = [],
        CurrentdisplayTarget = null,
        CurrentPickerType = 'year',
        eventhandlers = {
            'timepicker display list item': {
                'click' () {
                    var type = YangRAM.attr(this, 'type'),
                        value = parseInt(YangRAM.attr(this, 'value'));
                    switch (type) {
                        case 'year':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            builders.pickers.YearsListbuild(CurrentFirstYear, value);
                            break;
                        case 'mon':
                        case 'month':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            builders.pickers.MonthsListbuild(value);
                            break;
                        case 'date':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            switch (CurrentInputType) {
                                case 'datetime':
                                case 'fulldate':
                                    var month = new _.data.Month(parseInt(CurrentValuesList[1]) + 1, parseInt(CurrentValuesList[0]));
                                    var firstDay = month.firstDay,
                                        count = month.length,
                                        selectedDate = value,
                                        hideDayOfWeek = false;
                                    break;
                                case 'dayofyear':
                                    var firstDay = 0;
                                    switch (parseInt(CurrentValuesList[0])) {
                                        case 2:
                                            var count = 29;
                                            break;
                                        case 4:
                                        case 6:
                                        case 9:
                                        case 11:
                                            var count = 30;
                                            break;
                                        default:
                                            var count = 31;
                                            break;
                                    }
                                    selectedDate = value,
                                        hideDayOfWeek = true;
                                    break;
                            }
                            builders.pickers.DatesListbuild(firstDay, count, selectedDate, hideDayOfWeek);
                            break;
                        case 'hour':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            builders.pickers.HoursListbuild(value);
                            break;
                        case 'minute':
                        case 'first':
                        case 'second':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            builders.pickers.MinutesListbuild(value);
                            break;
                        case 'day':
                            CurrentdisplayTarget = this;
                            Launchers.clearPickers(type);
                            builders.pickers.DaysListbuild(value);
                            break;
                    }
                }
            },
            'timepicker buttons click': {
                'click' () {
                    var type = YangRAM.attr(this, 'type');
                    switch (type) {
                        case 'reset':
                            TimePicker.launch(CurrentInputValue, CurrentInputType);
                            break;
                        case 'cancel':
                            Launchers.clearDisplay();
                            Launchers.clearPickers();
                            TimePicker.off();
                            break;
                        case 'confirm':
                            ConfirmCallback(Result());
                            Launchers.clearDisplay();
                            Launchers.clearPickers();
                            TimePicker.off();
                            break;
                    }
                }
            },
            'timepicker pickers list item[value]': {
                'click' () {
                    var value = parseInt(YangRAM.attr(this, 'value'));
                    YangRAM.$('timepicker pickers list [selected]').removeAttr('selected');
                    YangRAM.attr(this, 'selected', 'selected').attr(CurrentdisplayTarget, 'value', value);
                    ResetValue(value);
                    CurrentdisplayTarget.innerHTML = builders.display.Format(CurrentPickerType, value);
                }
            },
            'timepicker pickers list item[prev]': {
                'click' () {
                    CurrentFirstYear -= 60;
                    if (CurrentFirstYear == -59) {
                        CurrentFirstYear = -60;
                    }
                    builders.pickers.YearsListbuild(CurrentFirstYear, parseInt(CurrentValuesList[0]));
                }
            },
            'timepicker pickers list item[next]': {
                'click' () {
                    CurrentFirstYear += 60;
                    if (CurrentFirstYear == 0) {
                        CurrentFirstYear = 1;
                    }
                    builders.pickers.YearsListbuild(CurrentFirstYear, parseInt(CurrentValuesList[0]));
                }
            },
            'application oiml view input[use-timepicker]': {
                'click' () {
                    var picktype = YangRAM.attr(this, 'pick-data-type') || 'datetime';
                    YangRAM.tools.pickTime(this.value, picktype, (val) => {
                        this.value = val;
                    });
                }
            }
        },
        ResetValue = (value) => {
            switch (CurrentInputType) {
                case 'datetime':
                case 'fulldate':
                    switch (CurrentPickerType) {
                        case 'year':
                            CurrentValuesList[0] = value;
                            break;
                        case 'mon':
                            CurrentValuesList[1] = value;
                            break;
                        case 'date':
                            CurrentValuesList[2] = value;
                            break;
                        case 'hour':
                            CurrentValuesList[3] = value;
                            break;
                        case 'first':
                            CurrentValuesList[4] = value;
                            break;
                        case 'second':
                            CurrentValuesList[5] = value;
                            break;
                    }
                    break;

                case 'dayofyear':
                    switch (CurrentPickerType) {
                        case 'month':
                            CurrentValuesList[0] = value;
                            break;
                        case 'date':
                            CurrentValuesList[1] = value;
                            break;
                    }
                    break;

                case 'timeofday':
                    switch (CurrentPickerType) {
                        case 'hour':
                            CurrentValuesList[0] = value;
                            break;
                        case 'first':
                            CurrentValuesList[1] = value;
                            break;
                        case 'second':
                            CurrentValuesList[2] = value;
                            break;
                    }
                    break;

                case 'hourminute':
                    switch (CurrentPickerType) {
                        case 'hour':
                            CurrentValuesList[0] = value;
                            break;
                        case 'minute':
                            CurrentValuesList[1] = value;
                            break;
                    }
                    break;

                case 'dayofweek':
                    CurrentValuesList[0] = value;
                    break;
            }
        },
        Result = () => {
            switch (CurrentInputType) {
                case 'datetime':
                    return CurrentValuesList[0] +
                        '-' + builders.display.Format('mon', CurrentValuesList[1]) +
                        '-' + builders.display.Format('date', CurrentValuesList[2]) +
                        ' ' + builders.display.Format('hour', CurrentValuesList[3]) +
                        ':' + builders.display.Format('first', CurrentValuesList[4]) +
                        ':' + builders.display.Format('second', CurrentValuesList[5]);

                case 'fulldate':
                    return CurrentValuesList[0] +
                        '-' + builders.display.Format('mon', CurrentValuesList[1]) +
                        '-' + builders.display.Format('date', CurrentValuesList[2]);

                case 'dayofyear':
                    return builders.display.Format('m', CurrentValuesList[0]) +
                        '-' + builders.display.Format('date', CurrentValuesList[1]);

                case 'timeofday':
                    return builders.display.Format('hour', CurrentValuesList[0]) +
                        ':' + builders.display.Format('first', CurrentValuesList[1]) +
                        ':' + builders.display.Format('second', CurrentValuesList[2]);

                case 'hourminute':
                    return builders.display.Format('hour', CurrentValuesList[0]) +
                        ':' + builders.display.Format('minute', CurrentValuesList[1]);

                case 'dayofweek':
                    return CurrentValuesList[0];
            }
        },
        Launchers = {
            clearDisplay() {
                _.each(TimePicker.display, (i, el) => {
                    _.dom.removeAttr(el, 'actived');
                });
            },
            clearPickers(type) {
                if (CurrentPickerType != type) {
                    YangRAM.$('timepicker outclicle pickers sheet').removeAttr('actived');
                    CurrentPickerType = type;
                }
            },
            datetime() {
                checkInputValue[CurrentInputType]();
                if (CurrentValuesList[0] > 0) {
                    var quotient = parseInt((parseInt(CurrentValuesList[0]) - 1) / 60);
                } else {
                    var quotient = parseInt((parseInt(CurrentValuesList[0]) - 61) / 60);
                }
                CurrentFirstYear = quotient * 60 + 1;
                builders.display.datetime(parseInt(CurrentValuesList[0]), CurrentValuesList[1], parseInt(CurrentValuesList[2]),
                    parseInt(CurrentValuesList[3]), parseInt(CurrentValuesList[4]), parseInt(CurrentValuesList[5]));
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=datetime] item');
                CurrentPickerType = 'year';
                YangRAM.attr(TimePicker.display.datetime, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.YearsListbuild(CurrentFirstYear, parseInt(CurrentValuesList[0]));
                }, 500);
            },
            fulldate() {
                checkInputValue[CurrentInputType]();
                if (CurrentValuesList[0] > 0) {
                    var quotient = parseInt((parseInt(CurrentValuesList[0]) - 1) / 60);
                } else {
                    var quotient = parseInt((parseInt(CurrentValuesList[0]) - 61) / 60);
                }
                CurrentFirstYear = quotient * 60 + 1;
                builders.display.fulldate(parseInt(CurrentValuesList[0]), CurrentValuesList[1], parseInt(CurrentValuesList[2]));
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=fulldate] item');
                CurrentPickerType = 'year';
                YangRAM.attr(TimePicker.display.fulldate, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.YearsListbuild(CurrentFirstYear, parseInt(CurrentValuesList[0]));
                }, 500);
            },
            localmonth() {},
            shortmonth() {},
            numbermonth() {},
            dayofyear() {
                checkInputValue[CurrentInputType]();
                CurrentValuesList[1] = parseInt(CurrentValuesList[1]) - 1;
                builders.display.dayofyear(parseInt(CurrentValuesList[0]), CurrentValuesList[1]);
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=dayofyear] item');
                CurrentPickerType = 'month';
                YangRAM.attr(TimePicker.display.dayofyear, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.MonthsListbuild(parseInt(CurrentValuesList[0]) - 1);
                }, 500);
            },
            timeofday() {
                checkInputValue[CurrentInputType]();
                builders.display.timeofday(parseInt(CurrentValuesList[0]), parseInt(CurrentValuesList[1]), parseInt(CurrentValuesList[2]));
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=timeofday] item');
                CurrentPickerType = 'hour';
                YangRAM.attr(TimePicker.display.timeofday, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.HoursListbuild(parseInt(CurrentValuesList[0]));
                }, 500);
            },
            hourminute() {
                checkInputValue[CurrentInputType]();
                builders.display.hourminute(parseInt(CurrentValuesList[0]), parseInt(CurrentValuesList[1]));
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=hourminute] item');
                CurrentPickerType = 'hour';
                YangRAM.attr(TimePicker.display.hourminute, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.HoursListbuild(parseInt(CurrentValuesList[0]));
                }, 500);
            },
            dayofweek() {
                checkInputValue[CurrentInputType]();
                builders.display.dayofweek(parseInt(CurrentValuesList[0]));
                CurrentdisplayTarget = document.querySelector('timepicker display list[type=dayofweek] item');
                CurrentPickerType = 'day';
                YangRAM.attr(TimePicker.display.dayofweek, 'actived', 'actived');
                setTimeout(() => {
                    builders.pickers.DaysListbuild(parseInt(CurrentValuesList[0]));
                }, 500);
            }
        },
        checkInputValue = {
            datetime() {
                CurrentInputValue = CurrentInputValue.trim();
                if (/^\-\d+\s*\-\s*\d{1,2}\s*\-\s*\d{1,2}\s+\d{1,2}\s*:\s*\d{1,2}\s*:\s*\d{1,2}$/.test(CurrentInputValue)) {
                    CurrentValuesList = CurrentInputValue.replace(/^\-/, '').split(/[\-\s\:]+/);
                    CurrentValuesList[0] = CurrentValuesList[0] * -1;
                } else {
                    if (/^\d+\s*\-\s*\d{1,2}\s*\-\s*\d{1,2}\s+\d{1,2}\s*:\s*\d{1,2}\s*:\s*\d{1,2}$/.test(CurrentInputValue) == false) {
                        CurrentInputValue = new _.util.Time().format('yyyy-MM-dd hh:mm:ss');
                    }
                    CurrentValuesList = CurrentInputValue.split(/[\-\s\:]+/);
                }
                if (CurrentValuesList[0] == 0) {
                    CurrentValuesList[0] = 1;
                }
                CurrentValuesList[1] = parseInt(CurrentValuesList[1]) - 1;
            },
            fulldate() {
                CurrentInputValue = CurrentInputValue.trim();
                if (/^\-\d+\s*\-\s*\d{1,2}\s*\-\s*\d{1,2}\s+\d{1,2}\s*:\s*\d{1,2}\s*:\s*\d{1,2}$/.test(CurrentInputValue)) {
                    CurrentValuesList = CurrentInputValue.replace(/^\-/, '').split(/[\-\s\:]+/);
                    CurrentValuesList[0] = CurrentValuesList[0] * -1;
                } else {
                    if (/^\d+\s*\-\s*\d{1,2}\s*\-\s*\d{1,2}$/.test(CurrentInputValue) == false) {
                        CurrentInputValue = new _.util.Time().format('yyyy-MM-dd');
                    }
                    CurrentValuesList = CurrentInputValue.split(/[\-\s]+/);
                }
                if (CurrentValuesList[0] == 0) {
                    CurrentValuesList[0] = 1;
                }
                CurrentValuesList[1] = parseInt(CurrentValuesList[1]) - 1;
            },
            localmonth() {},
            shortmonth() {},
            numbermonth() {},
            dayofyear() {
                CurrentInputValue = CurrentInputValue.trim();
                if (/^\d{1,2}\s*\-\s*\d{1,2}$/.test(CurrentInputValue) == false) {
                    CurrentInputValue = new _.util.Time().format('yyyy-MM');
                }
                CurrentValuesList = CurrentInputValue.split(/[\-\s]+/);
                CurrentValuesList[1] = parseInt(CurrentValuesList[1]) - 1;
            },
            timeofday() {
                CurrentInputValue = CurrentInputValue.trim();
                if (/^\d{1,2}\s*:\s*\d{1,2}\s*:\s*\d{1,2}$/.test(CurrentInputValue) == false) {
                    CurrentInputValue = new _.util.Time().format('hh:mm:ss');
                }
                CurrentValuesList = CurrentInputValue.split(/[\:\s]+/);
            },
            hourminute() {
                CurrentInputValue = CurrentInputValue.trim();
                if (/^\d{1,2}\s*:\s*\d{1,2}$/.test(CurrentInputValue) == false) {
                    CurrentInputValue = new _.util.Time().format('hh:mm');
                }
                CurrentValuesList = CurrentInputValue.split(/[\:\s]+/);
            },
            dayofweek() {
                CurrentInputValue = parseInt(CurrentInputValue.trim());
                if (CurrentInputValue > 6 || CurrentInputValue < 0) {
                    CurrentInputValue = 0;
                }
                CurrentValuesList = [CurrentInputValue.trim()];
            }
        };

    _.extend(TimePicker, true, {
        build() {
            this.Circle = YangRAM.create('outclicle', this.document);
            var display = YangRAM.create('display', this.Circle),
                Buttons = YangRAM.create('buttons', this.Circle, {
                    html: '<click type="reset">Reset</click><click type="cancel">Cancel</click><click type="confirm">Confirm</click>'
                }),
                pickers = YangRAM.create('pickers', this.Circle);
            this.display = {
                datetime: YangRAM.create('list', display, { type: 'datetime' }),
                fulldate: YangRAM.create('list', display, { type: 'fulldate' }),
                dayofyear: YangRAM.create('list', display, { type: 'dayofyear' }),
                timeofday: YangRAM.create('list', display, { type: 'timeofday' }),
                hourminute: YangRAM.create('list', display, { type: 'hourminute' }),
                dayofweek: YangRAM.create('list', display, { type: 'dayofweek' })
            }

            this.pickers = {
                YearsPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'years' }),
                MonthsPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'months' }),
                DatesPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'dates' }),
                HoursPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'hours' }),
                MinutesPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'minutes' }),
                DaysPicker: YangRAM.create('sheet', pickers, { html: '<list></list>', type: 'days' })
            }

            builders = System.ModuleSeeds.apis.TimePickerBuilders(YangRAM, declare, global, undefined);
            return this.listenEvents(eventhandlers);
        },
        launch(value, type, callback) {
            TimePicker.on();
            Launchers.clearDisplay();
            Launchers.clearPickers();
            CurrentInputValue = value;
            if (_.util.bool.isFn(callback)) {
                ConfirmCallback = callback;
            }
            switch (type) {
                case 'fulldate':
                    CurrentInputType = type;
                    //CurrentInputValue = '2016-02-26';
                    Launchers.fulldate();
                    break;
                case 'dayofyear':
                    CurrentInputType = type;
                    //CurrentInputValue = '2-26';
                    Launchers.dayofyear();
                    break;
                case 'localmonth':
                    break;
                case 'shortmonth':
                    break;
                case 'numbermonth':
                    break;
                case 'timeofday':
                    CurrentInputType = type;
                    //CurrentInputValue = '13:30:00';
                    Launchers.timeofday();
                    break;
                case 'hourminute':
                    CurrentInputType = type;
                    //CurrentInputValue = '13:30';
                    Launchers.hourminute();
                    break;
                case 'dayofweek':
                    CurrentInputType = type;
                    //CurrentInputValue = '0';
                    Launchers.dayofweek();
                    break;
                default:
                    CurrentInputType = 'datetime';
                    //CurrentInputValue = '2016-02-26 13:30:00';
                    Launchers.datetime();
            }
        }
    }).build();
});