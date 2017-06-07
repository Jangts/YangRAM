System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        TimePicker = System.TimePicker,
        _ = System.Pandora;

    var months = ['JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER'],
        mons = _.locales('times', 'en', 'monthsShort'),
        daysofweek = _.locales('times', 'en', 'days'),
        days = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

    var yearslist = document.querySelector('pickers sheet[type=years] list'),
        monthslist = document.querySelector('pickers sheet[type=months] list'),
        dateslist = document.querySelector('pickers sheet[type=dates] list'),
        hourslist = document.querySelector('pickers sheet[type=hours] list'),
        minuteslist = document.querySelector('pickers sheet[type=minutes] list'),
        dayslist = document.querySelector('pickers sheet[type=days] list');

    var Format = (type, value) => {
        value = parseInt(value);
        switch (type) {
            case 'month':
                return months[value]

            case 'day':
                return daysofweek[value]

            case 'm':
                return value + 1

            case 'mon':
                value++
            case 'date':
            case 'hour':
            case 'minute':
            case 'first':
            case 'second':
                if (value > 9) {
                    return value;
                }
                return '0' + value;
            case 'y':
                if (value == 1) {
                    return '元年';
                }
            case 'year':
                if (value < 0) {
                    return Math.abs(value) + '<el bctag></el>';
                }
            default:
                return value;
        }
    };

    return {
        display: {
            Format,
            datetime(year, month, day, hour, minute, second) {
                var html = '<item type="year" value="' + year + '">' + Format('year', year) + '</item>';
                html += '<item type="mon" value="' + month + '">' + Format('mon', month) + '</item>';
                html += '<item type="date" value="' + day + '">' + Format('date', day) + '</item>';
                html += '<item type="hour" value="' + hour + '">' + Format('hour', hour) + '</item>';
                html += '<item type="first" value="' + minute + '">' + Format('first', minute) + '</item>';
                html += '<item type="second" value="' + second + '">' + Format('second', second) + '</item>';
                TimePicker.display.datetime.innerHTML = html;
            },
            fulldate(year, month, day) {
                var html = '<item type="year" value="' + year + '">' + Format('year', year) + '</item>';
                html += '<item type="mon" value="' + month + '">' + Format('mon', month) + '</item>';
                html += '<item type="date" value="' + day + '">' + Format('date', day) + '</item>';
                TimePicker.display.fulldate.innerHTML = html;
            },
            dayofyear(month, day) {
                var html = '<item type="month" value="' + month + '">' + Format('month', month) + '</item>';
                html += '<item type="date" value="' + day + '">' + Format('date', day) + '</item>';
                TimePicker.display.dayofyear.innerHTML = html;
            },
            timeofday(hour, minute, second) {
                var html = '<item type="hour" value="' + hour + '">' + Format('hour', hour) + '</item>';
                html += '<item type="first" value="' + minute + '">' + Format('first', minute) + '</item>';
                html += '<item type="second" value="' + second + '">' + Format('second', second) + '</item>';
                TimePicker.display.timeofday.innerHTML = html;
            },
            hourminute(hour, minute) {
                var html = '<item type="hour" value="' + hour + '">' + Format('hour', hour) + '</item>';
                html += '<item type="minute" value="' + minute + '">' + Format('minute', minute) + '</item>';
                TimePicker.display.hourminute.innerHTML = html;
            },
            dayofweek(day) {
                TimePicker.display.dayofweek.innerHTML = '<item type="day" value="' + day + '">' + Format('day', day) + '</item>';
            }
        },
        pickers: {
            YearsListbuild(firstYear, selectedYear) {
                var year = firstYear,
                    lastYear = firstYear + 59;

                var html = '<item prev>Prev Page</item>';
                for (year; year <= lastYear; year++) {
                    if (year === selectedYear) {
                        if (year % 4) {
                            html += '<item selected value="' + year + '">' + Format('y', year) + '</item>';
                        } else {
                            html += '<item leap selected value="' + year + '">' + Format('y', year) + '</item>';
                        }
                    } else {
                        if (year % 4) {
                            html += '<item value="' + year + '">' + Format('y', year) + '</item>';
                        } else {
                            html += '<item leap value="' + year + '">' + Format('y', year) + '</item>';
                        }
                    }
                }
                html += '<item next>Next Page</item>';
                yearslist.innerHTML = html;
                YangRAM.attr(yearslist.parentNode, 'actived', 'actived');
            },
            MonthsListbuild(selectedMonth) {
                var html = '';
                for (var month = 0; month < 12; month++) {
                    if (month === selectedMonth) {
                        if (month < 3) {
                            html += '<item spring selected value="' + month + '">' + mons[month] + '</item>';
                        } else if (month < 6) {
                            html += '<item summer selected value="' + month + '">' + mons[month] + '</item>';
                        } else if (month < 9) {
                            html += '<item autumn selected value="' + month + '">' + mons[month] + '</item>';
                        } else {
                            html += '<item winter selected value="' + month + '">' + mons[month] + '</item>';
                        }
                    } else {
                        if (month < 3) {
                            html += '<item spring value="' + month + '">' + mons[month] + '</item>';
                        } else if (month < 6) {
                            html += '<item summer value="' + month + '">' + mons[month] + '</item>';
                        } else if (month < 9) {
                            html += '<item autumn value="' + month + '">' + mons[month] + '</item>';
                        } else {
                            html += '<item winter value="' + month + '">' + mons[month] + '</item>';
                        }
                    }
                }
                monthslist.innerHTML = html;
                YangRAM.attr(monthslist.parentNode, 'actived', 'actived');
            },
            DatesListbuild(firstDay, count, selectedDate, hideDayOfWeek) {
                var d = day = 0,
                    lastDay = (firstDay + count - 1) % 7;
                var date = 1,
                    lastDate = count;

                var html = '';
                if (!hideDayOfWeek) {
                    for (d; d < 7; d++) {
                        html += '<item head>' + days[d] + '</item>';
                    }

                    for (d = 0; d < 7; d++) {
                        html += '<item head>' + days[d] + '</item>';
                    }
                }
                for (day; day < firstDay; day++) {
                    html += '<item placeholder></item>';
                }
                for (date; date <= lastDate; date++) {
                    if (date === selectedDate) {
                        if ((date + firstDay) % 7 === 0 || (date + firstDay) % 7 === 1) {
                            html += '<item weekend selected value="' + date + '">' + date + '</item>';
                        } else {
                            html += '<item selected value="' + date + '">' + date + '</item>';
                        }
                    } else {
                        if ((date + firstDay) % 7 === 0 || (date + firstDay) % 7 === 1) {
                            html += '<item weekend value="' + date + '">' + date + '</item>';
                        } else {
                            html += '<item value="' + date + '">' + date + '</item>';
                        }
                    }
                }
                for (lastDay; lastDay < 7; lastDay++) {
                    html += '<item placeholder></item>';
                }
                dateslist.innerHTML = html;
                YangRAM.attr(dateslist.parentNode, 'actived', 'actived');
            },
            HoursListbuild(selectedHour) {
                var html = '';
                for (var hour = 0; hour <= 23; hour++) {
                    if (hour === selectedHour) {
                        html += '<item selected value="' + hour + '">' + hour + '</item>';
                    } else if (hour % 6) {
                        if (hour < 12) {
                            html += '<item am value="' + hour + '">' + hour + '</item>';
                        } else {
                            html += '<item pm value="' + hour + '">' + hour + '</item>';
                        }
                    } else {
                        if (hour < 12) {
                            html += '<item am int value="' + hour + '">' + hour + '</item>';
                        } else {
                            html += '<item pm int value="' + hour + '">' + hour + '</item>';
                        }
                    }
                }
                hourslist.innerHTML = html;
                YangRAM.attr(hourslist.parentNode, 'actived', 'actived');
            },
            MinutesListbuild(selectedMinute) {
                var html = '';
                for (var minute = 0; minute <= 59; minute++) {
                    if (minute === selectedMinute) {
                        if (minute % 5) {
                            html += '<item selected value="' + minute + '">' + minute + '</item>';
                        } else {
                            html += '<item int selected value="' + minute + '">' + minute + '</item>';
                        }
                    } else {
                        if (minute % 5) {
                            html += '<item value="' + minute + '">' + minute + '</item>';
                        } else {
                            html += '<item int value="' + minute + '">' + minute + '</item>';
                        }
                    }
                }
                minuteslist.innerHTML = html;
                YangRAM.attr(minuteslist.parentNode, 'actived', 'actived');
            },
            DaysListbuild(selectedDay) {
                var html = '';
                for (var day = 0; day < 7; day++) {
                    if (day === selectedDay) {
                        if (day > 0 && day < 5) {
                            html += '<item selected value="' + day + '">' + days[day] + '</item>';
                        } else {
                            html += '<item weekend selected value="' + day + '">' + days[day] + '</item>';
                        }
                    } else {
                        if (day > 0 && day < 5) {
                            html += '<item value="' + day + '">' + days[day] + '</item>';
                        } else {
                            html += '<item weekend value="' + day + '">' + days[day] + '</item>';
                        }
                    }
                }
                dayslist.innerHTML = html;
                YangRAM.attr(dayslist.parentNode, 'actived', 'actived');
            }
        }
    };

}, 'TimePickerBuilders');