Instructions = {
    Srch: {
        RegExps: {
            Search: /^(搜索|检索|查询)\s*(.+)$/,
        },
        Handlers: {
            Search(array) {
                this.Keyword = array[2];
                this.Searcher();
            },
        },
    },
    Qery: {
        RegExps: {
            Query: /^query\s*->\s*(.+?)(\s*;\s*)?$/,
        },
        Handlers: {
            Query(array) {
                this.Sentence = array[1];
                this.Querier();
            },
        },
    },
    Ctrl: {
        RegExps: {
            Settings: /^settings\s*:\s*(\w+)\s*(->\s*(.+?))?(\s*;\s*)?$/,
        },
        Handlers: {
            Settings(array) {
                this.Command = array[1];
                this.Parameters = array[3] || '';
                this.Setter();
            },
        },
    },
    Open: {
        RegExps: {
            Index: /^(打开|前往|查看)?\s*(主页|首页|前台)$/,
        },
        Handlers: {
            Index() {
                global.open('/');
                return '已在新窗口打开站点主页。';
            },
        },
    },
    Self: {
        RegExps: {
            SizeWide: /^(to max|be max|变大|变大些|窗口变大|放大|窗放大|最大化)$/,
            SiseNrml: /^(to min|be max|变小|变小些|还原|窗口还原|缩小|窗口缩小)$/,
            Sleep: /^(bye-bye|拜拜|再见|see\s+you|c\s*u|bye|关闭搜索|收起搜索|收起来|隐藏搜索|最小化)$/i,
            Clear: /^(CLS|clear screen|清屏)$/i,
        },
        Handlers: {
            SizeWide() {
                this.setViewStatus('widemode');
                return '已切换至宽屏模式。';
            },
            SiseNrml() {
                this.setViewStatus('nrmlmode');
                return '已切换至普通模式。';
            },
            Sleep() {
                this.sleep();
                return null;
            },
            Clear() {
                this.Clear();
                return null;
            }
        },
    },
    Apps: {
        RegExps: {
            Launch: /^(launch\s|open\s|start\s|app\s|开启|打开|启动)(.+)$/i,
            Close: /^(close\s+|exit\s\+|close:|exit:|关闭|退出|关掉)/i,
        },
        Handlers: {
            Launch(array) {

            },
            Close(array) {},
        },
    },
    Calc: {
        RegExps: {
            PlusA: /^(\d+(\.\d+)?)\s*(\+|add|plus|加|加上)\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            PlusB: /^(\d+(\.\d+)?)\s*(与|和)\s*(\d+(\.\d+)?)\s*(的和|的和为|的和是|之和|之和为|之和是)(多少|几)?(\?|？)?$/,
            MinusA: /^(\d+(\.\d+)?)\s*(-|minus|减去|减)\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            MinusB: /^(\d+(\.\d+)?)\s*(与|和)\s*(\d+(\.\d+)?)\s*(的差|的差为|的差是|之差|之差为|之差是)(多少|几)?(\?|？)?$/,
            MultiplyA: /^(\d+(\.\d+)?)\s*(\*|乘|乘以|个|times)\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            MultiplyB: /^(\d+(\.\d+)?)\s*(与|和)\s*(\d+(\.\d+)?)\s*(的积|的积为|的积是|之积|之积为|之积是|的乘积|的乘积为|的乘积是)(多少|几)?(\?|？)?$/,
            DivideA: /^(\d+(\.\d+)?)\s*(\/|÷|除以|divide)\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            DivideB: /^(\d+(\.\d+)?)\s*(与|和)\s*(\d+(\.\d+)?)\s*(的商|的商为|的商是|之商|之商为|之商是)(多少|几)?(\?|？)?$/,
            DivideC: /^(\d+(\.\d+)?)\s*除\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            ResidueA: /^(\d+(\.\d+)?)\s*%\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            ResidueB: /^(\d+(\.\d+)?)\s*(\/|÷|除以|divide)\s*(\d+(\.\d+)?)\s*的余数(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            ResidueC: /^(\d+(\.\d+)?)\s*(\/|÷|除以|divide)\s*(\d+(\.\d+)?)\s*余多少(\?|？)?$/,
            ResidueD: /^(\d+(\.\d+)?)\s*(与)\s*(\d+(\.\d+)?)\s*(的模|的模为|的模是)(多少|几)?(\?|？)?$/,
            PowerA: /^(\d+(\.\d+)?)\s*的\s*(\d+(\.\d+)?)\s*次方(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            PowerB: /^(\d+(\.\d+)?)\s*\^\s*(\d+(\.\d+)?)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            Square: /^(\d+(\.\d+)?)\s*(的平方|的二次方|^2)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            Cube: /^(\d+(\.\d+)?)\s*(的立方|的三次方|^3)\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            Root: /^(\d+(\.\d+)?)\s*的\s*(\d+(\.\d+)?)\s*次方根\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            SquareRoot: /^(\d+(\.\d+)?)的平方根\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
            CubeRoot: /^(\d+(\.\d+)?)的立方根\s*(=|等于|等于多少|是|是多少|等于几|是几|是几多)?(\?|？)?$/,
        },
        Handlers: {
            PlusA(array) {
                return '= ' + (Number(array[1]) + Number(array[4]));
            },
            PlusB(array) {
                return '= ' + (Number(array[1]) + Number(array[4]));
            },
            MinusA(array) {
                return '= ' + (Number(array[1]) - Number(array[4]));
            },
            MinusB(array) {
                return '= ' + (Number(array[1]) - Number(array[4]));
            },
            MultiplyA(array) {
                return '= ' + (Number(array[1]) * Number(array[4]));
            },
            MultiplyB(array) {
                return '= ' + (Number(array[1]) * Number(array[4]));
            },
            DivideA(array) {
                return '= ' + (Number(array[1]) / Number(array[4]));
            },
            DivideB(array) {
                return '= ' + (Number(array[1]) / Number(array[4]));
            },
            DivideC(array) {
                return '= ' + (Number(array[3]) / Number(array[1]));
            },
            ResidueA(array) {
                return '= ' + (Number(array[1]) % Number(array[3]));
            },
            ResidueB(array) {
                return '= ' + (Number(array[1]) % Number(array[4]));
            },
            ResidueC(array) {
                return '= ' + (Number(array[1]) % Number(array[4]));
            },
            PowerA(array) {
                return '= ' + (Math.pow(Number(array[1]), Number(array[3])));
            },
            PowerB(array) {
                return '= ' + (Math.pow(Number(array[1]), Number(array[3])));
            },
            Square(array) {
                return '= ' + (Math.pow(Number(array[1]), 2));
            },
            Cube(array) {
                return '= ' + (Math.pow(Number(array[1]), 3));
            },
            Root(array) {
                return '= ' + (Math.pow(Number(array[1]), 1 / Number(array[3])));
            },
            SquareRoot(array) {
                return '= ' + (Math.sqrt(Number(array[1])));
            },
            CubeRoot(array) {
                return '= ' + (Math.pow(Number(array[1]), 1 / 3));
            },
        },
    },
    Time: {
        RegExps: {
            FullA: /^(time|time now|show time|whats the time|whats the time now|what is the time|what is the time now|what's the time|what's the time now|currTime)$/i,
            FullB: /(时间|当前时间|显示时间|什么时候了|什么时间|几点了|现在几点|现在什么时间|现在几点了|报时)(\?|？)?/i,
            AlarmA: /(闹钟|闹铃)/i,
            AlarmB: /(闹钟|闹铃)/i,
            CheckDate: /日历/i,
        },
        Handlers: {
            FullA() {
                return '当前时间为：' + System.HiBar.Timer.clock();
            },
            FullB() {
                return '当前时间为：' + System.HiBar.Timer.clock();
            },
            AlarmA() {},
            AlarmB() {},
            CheckDate() {},
        },
    },
    Play: {
        RegExps: {
            MusicA: /^(play|播放|放首|放一首|来首|来一首)\s*(.+)$/i,
            MusicB: /^(随便|随机)?(play a song|music|play a music|放首歌|放首歌听听|放首音乐|音乐|播放音乐|音乐搞起)$/i,
        },
        Handlers: {
            MusicA(array) {
                return '抱歉，没有找到与"' + array[2] + '"相关的歌曲';
            },
            MusicB() {
                return '抱歉，您的乐库里好像没有什么音乐！';
            }
        },
    },
    Life: {
        RegExps: {
            Weather: /天气/,
            Message: /消息/,
        },
        Handlers: {
            Weather() {},
            Message() {},
        },
    },
    Dail: {
        RegExps: {
            Greet: /^(hi|hey|hello|你好|嘿|哈喽|早上好|早安|午安|中午好|下午好|晚上好)(,|，)?\s*(smartian)?(!|~|！)?\s*$/i,
            Name: /(名字|你叫什么|叫什么|你的名字|你的名字是什么|你的名字叫什么|你是谁|你叫|你是|怎么称呼|怎么称呼你|称呼)(\?|？)?\s*/i,
        },
        Handlers: {
            Greet() {
                Instructions.Dail.TimesCount.Greet++;
                if (Instructions.Dail.TimesCount.Name < 20) {
                    return this.Keyword.replace(/smartian/gi, System.User);
                } else {
                    return '我只是个搜索引擎，并非智能型私人助理，请不要试图与一个引擎对话';
                }
            },
            Name() {
                Instructions.Dail.TimesCount.Name++;
                var array = [
                    'Smartian.',
                    '我叫Smartian.',
                    '我是Smartian.',
                    '叫我Smartian吧.',
                    'Smartian, 你觉得中文译为司马甜怎么样？'
                ];
                var i = Math.ceil(Math.random() * 4);
                if (Instructions.Dail.TimesCount.Name < 3) {
                    return array[i];
                } else if (Instructions.Dail.TimesCount.Name < 4) {
                    return '最后说一次，Smartian, 不要再问了哦~';
                } else {
                    return '我只是个搜索引擎，并非智能型私人助理，请不要试图与一个引擎对话';
                }

            }
        },
        TimesCount: {
            Greet: 0,
            Name: 0
        }
    }
};