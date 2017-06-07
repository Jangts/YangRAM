System.Runtime.locales = {
    CODE: 'zh-cn',

    COMMON: {
        EMPTY_FN_TRIGGERED: '你触发了一个空的函数!',
        HELLO: '你好，YangRAM！',
        BYE_BYE: '再见，YangRAM！',
        OF_NOT_FOUND: '找不到操作脚本文件',
        LAUNCH_SUCCESS: [
            '应用已成功开启',
            function(name) {
                return '您打开了应用 [' + name + ']，请尽情使用吧.';
            }
        ],
        AF_NOT_FOUND: '对不起，未能加载该应用的项目文件, 请检查您的网络连接，或者重装该应用。',
        AP_NOT_FOUND: '对不起，未找到应用所需的闭包, 请重装该应用。',
        MF_NOT_FOUND: '对不起，未定义应用的主函数, 请重装该应用。',
        UNKNOWN_MISTAKE: '未知错误',
        UM_TIPS: {
            title: '应用错误',
            content: '应用出现了一些问题！',
            confirm: '关闭应用',
            cancel: '忽略警告'
        },
        COPY: [
            '复制成功',
            '文本已被成功复制到YangRAM的剪切板'
        ],
        ERR_APP_ID: '错误的应用标识',
        CLOSE: {
            title_E: '应用仍在工作中',
            content_E: function(name) {
                return name + ' 仍在工作，仍旧关闭该应用，还是检查该应用正在进行的任务？';
            },
            resolve: '关闭',
            reject: '检查',
            cancel: '取消',
            title_N: '关闭应用',
            content_N: '确定要关闭该应用吗?',
        },
        TOPMENUS: {
            'Common': '常用',
            'Operate': '操作',
            'New': '新建',
            'Create': '创建',
            'Open': '打开',
            'Edit': '编辑',
            'Modifies': '修改',
            'Save': '保存',
            'Remove': '移除',
            'Delete': '删除',
            'Recover': '还原',

            'List': '列表',
            'Search': '搜索',
            'Seek': '查找',
            'Check': '查看',

            'View': '显示',
            'Pageview': '显示',
            'Center': '居中',
            'Cover': '铺开',
            'Sleep': '停靠',
            'Close': '关闭',

            'Formats': '格式',
            'Plug-ins': '插件',
            'Orders': '命令',
            'Settings': '设置',
            'Help': '帮助'
        },
        WORDS: {
            'No Permissions!': '权限不足！',
            'Something Wrong!': '出现一点错误！',
            'Network Error!': '网络错误！',
            'Unkonw Error!': '未知错误！',
            'Operate Failed': '操作失败',
            'Cancel': '取消',
            'Complete': '已完成',
            'Confirm': '确认',
            'No Insert Area': '没有插入区'
        },
        ATTRS: {
            'Name': '名称',
            'Status': '状态',
            'FileSize': '文件大小',
            'Create Time': '创建时间',
            'Modify Time': '修改时间',
            'Operate': '操作'
        }
    },

    UOI: {
        WELCOME_T: '欢迎使用',
        WELCOME_C: function(time) {
            return '本次加载用时 ' + time + ' 豪秒';
        }
    },

    LOGGER: {
        APPNAME: '登录器',
        USERNAME: '用户名',
        PASSWORD: '密码',
        USERNAME_CANNOT_EMPTY: '用户名不能为空',
        PASSWORD_MUST_MORE_THEN: function(n) {
            return '密码必须是' + n + '位以上';
        },
        LOG_CHECK_SUCCEED: 'Succeed!',
        USERNAME_OR_PASSWORD_NOT_MATCH: '用户名或密码错误',
        LOG_CHECK_FAIL: '验证失败，请稍后再试!',
        LOGOFF_CHECK: [
            ', 等应用仍在工作中，\r\n',
            '你确定要登出系统吗?'
        ],
    },

    LOCKER: {
        APPNAME: '锁屏组件',
        NOTICE: {
            title: '欢迎回来。',
            content: function(time) {
                return '你已经离开了 ' + parseFloat(time).toFixed(1) + ' 分钟';
            }
        }
    },

    SYSTEM: {
        LOADSTATUS: [

            '开始加载...',

            '加载HiBar项目的样式表...',
            '加载导航目录的样式表...',
            '加载用户信息...',

            '加载日历样式表...',
            '加载日历事件数据和时钟...',

            '加载Smartian的样式表',
            '初始化Smartian指令集...',

            '加载Launcher的样式表...',
            '加载应用使用榜单的样式表...',
            '加载便利墙的样式表...',
            '加载便利强书签数据...',
            '载入所用安装应用...',

            '加载通知中心的样式表...',
            '加载通知与消息...',

            '加载内置浏览器的样式表...',
            '加载时间拾取器的样式表...',
            '加载色彩拾取器的样式表...',
            '加载项目选择器的样式表...',
            '加载百分值推选器的样式表...',

            '加载应用工作区的样式表...',
            '加载应用工作区列表模式下的样式表...',

            '加载动画样式表...',

            '加载OI框架 / 通用布局样式表...',
            '加载OI框架 / 通用颜色表...',
            '加载OI框架 / OIML Tabs 的样式表...',
            '加载OI框架 / OIML Slider 的样式表...',
            '加载OI框架 / OIML Slider Panel 的样式表...',
            '加载OI框架 / OIML Menu 的样式表...',
            '加载OI框架 / OIML Blocks 的样式表...',
            '加载OI框架 / OIML Paging List 的样式表...',

            '加载OI框架 / OIML Form 的样式表...',
            '加载OI框架 / OIML Radio And CheckBox 的样式表...',
            '加载OI框架 / OIML Edit Panel 的样式表...',

            '加载OI框架 / Modern Table 的样式表...',
            '加载OI框架 / Modern Form 的样式表...',

            '加载I4广场的样式表...',
            '加载设置的样式表...',
            '最后一步 : 启动I4广场...'
        ]
    },

    HIGHBAR: {
        APPNAME: 'HiBar',
        MENUS: {
            'Smartian': 'Smartian',
            'Msgcenter': '消息中心',
            'Kalendar': '日历',
            'Taskmgr': '任务管理器',
            'Center': '居中当前应用',
            'Cover': '展开当前应用',
            'Sleep': '收起当前应用',
            'Close': '关闭当前应用',
            'Frontpage': '前台首页',
            'Apps': '所有应用',
            'LogOff': '登出',
            'Lockscreen': '锁屏',
            'Tablet': '我的桌面',
            'Registry': '注册表',
            'tQuery': '命令行',
        },
        TAM: {
            APPNAME: '标题栏与菜单栏',
        },
        CTX: {
            APPNAME: '上下文菜单',
        },
        TMR: {
            WEEKS: {
                SUNDAY: '星期日',
                MONDAY: '星期一',
                TUESDAY: '星期二',
                WEDNESDAY: '星期三',
                THURSDAY: '星期四',
                FRIDAY: '星期五',
                SATURDAY: '星期六'
            },
            DAWN: '黎明',
            MORNIN: '上午',
            NOON: '正午',
            AFTNOON: '下午',
            EVENIN: '傍晚',
            NIGHT: '夜间',
            WEE: '凌晨',
            UNIT: {
                year: '年',
                month: '月',
                week: '周',
                day: '天',
                d: '天',
                hour: '小时',
                cnhour: '个时辰',
                h: '时',
                minute: '分钟',
                m: '分',
                second: '秒钟',
                s: '秒',
                msec: '毫秒',
                ms: '毫秒'
            }
        },
    },

    SMARTIAN: {
        APPNAME: 'Smartian',
    },

    NOTIFIER: {
        APPNAME: '消息中心',
        DEFAULT: {
            title: '来自YangRAM的对话框',
            content: '你好，欢迎使用YangRAM数据管理系统.',
            confirm: '确定',
            resolve: '是',
            reject: '否',
            cancel: '取消',
        }
    },

    PROCESSBUS: {
        APPNAME: '活动应用总控',
        TABLE_HEAD: {
            'App Name': '应用名称',
            'App Status': '应用状态',
            'Run Time': '运行时间',
            'Operation': '操作'
        },
        STATUS_AND_OPERATE: {
            'Actived': '活动中',
            'Running': '运行中',
            'UnKnow': '未知',
            'Sleeping': '休眠中',
            'CURRENT': '当前',
            'Logoff': '登出',
            'Restart': '重启',
            'CutTo': '切到',
            'Close': '关闭',
            'Show2D': '显示2D',
            'Show3D': '显示3D',
            'NoOperation': '无可用操作',
            'ReturnI4Plaza': '显示I4Plaza'
        }
    },

    KALENDAR: {
        APPNAME: '我的日历',
    },

    MAGICCUBE: {
        APPNAME: '动态魔术块',
    },

    DIALOG: {
        APPNAME: '通用对话框组件',
    },

    UPLOADER: {
        APPNAME: '通用上传组件',
        STATUS: {
            'ERR_INI_SIZE': "上传的文件大小超过了允许上传的文件大小的最大值",
            'ERR_FORM_SIZE': "上传的文件大小超过了允许发送的数据的最大值",
            'ERR_PARTIAL': "只有部分文件被上传",
            'ERR_NO_FILE': "没有文件被发送到服务器",
            'ERR_NO_TMP_DIR': "找不到临时文件夹，请联系管理员",
            'ERR_CANT_WRITE': "临时文件夹不可写，请联系管理员",
            'ERR_EXTENSION': "错误的上传信息",
            'Unknown Result': "未知错误"
        }
    },

    LAUNCHER: {
        APPNAME: 'Launcher',
        MMW: {
            APPNAME: '信息墙',
            MENUS: {
                'New Group': '新建组',
                'Refresh': '刷新',
                'Edit Group': '编辑组',
                'Delete Group': '删除组',
                'Editor Link': '编辑链接',
                'Delete Link': '删除链接',
                'New Link': '新建链接',
                'Sorting': '排序链接'
            }
        },
        ARL: {
            APPNAME: '应用使用榜',
            MENUS: {
                'Launch': '开启',
                'Sleep': '停靠',
                'Awake': '唤醒',
                'Close': '关闭',
                'Uninstall': '卸载',
                'On/Off': '开启/关闭',
                'New Group': '新建组',
                'Basic/General': '基本/通用',
                'Channel/Columns/Router': '频道/栏目/路由',
                'Content Presets': '内容预设置',
                'Applications/Themes': '应用/主题',
                'Driver/Protocol': '驱动/协议',
                'Operators Manage': '操作员管理',
                'Personal': '个性化',
                'Agency/Languges': '机构/语言',
                'Update/Safety': '更新/安全',
                'New Recycle Rlue': '新建回收规则',
                'Empty Recycling Items': '清空废纸篓',
                'Preset Contents': '预设内容',
                'Material Library': '素材库',
                'Pictures': '图片',
                'Documents': '一般文档',
                'Texts': '文本文档',
                'Compressed Files': '压缩包',
                'Videos': '视频',
                'Musics': '音频',
                'Other Archives': '其他文件',
            }
        },
        MGR: {
            APPNAME: 'Bookmark Manager'
        }
    },

    I4PLAZA: {
        APPNAME: 'I4广场',
        MENUS: {
            'System Settings': '系统设置',
            'App Store': '应用商城',
            'Task Manager': '任务管理',
            'Use Manual': '使用指南',
            'To Do List': '待办事项',
            'System Update': '系统更新',
            'YangRAM Official Website': 'YangRAM官网',
            'Forum': '交流论坛',
            'Developer Center': '开发者中心',
            'About YangRAM': '关于 YangRAM'
        }
    },

    EXPLORER: {
        APPNAME: 'YangRAM Explorer',
        MENUS: {
            'Default Page': '应用首页',
            'Refresh': '刷新应用',
            'Open Folder': '打开文件夹',
            'Move To': '移动到',
            'Rename': '重命名',
            'Delete Folder': '删除文件夹',
            'Delete Content': '删除内容',
            'Copy URL': '复制网址',
            'Replace': '替换',
            'Delete Documents': '删除文档',
            'Preview': '预览',
            'Copy Code': '复制代码',
            'Play': '播放',
            'Copy HTML Code': '复制HTML代码'
        },
        WORDS: {
            'No Support': '不支持',
            'May Timeout': '可能超时',
            'Different Formats!': '格式不一致！',
            'Type Not Support!': '不支持的格式！',
            'Filesize OVER!': '文件过大！',
            'No Legal File Selected!': '未选中有效文件',
            'Copy URL': '复制网址',
            'natural size': '原始尺寸',
            'Copy HTML Code': '复制HTML代码',
            'Copy File URL': '复制文件地址',
            'Can Not Contain <>/\|:"*?': '不能包含 <>/\|:"*?',
            'Can Not More Than 50 Words!': '不能超过50个字符！',
            'Can Not Empty!': '不能为空！',
            'File Not Exists!': '文件不存在！',
            'Rename Success': '易名成功!',
            'Rename_Success': function(name) {
                return '成功修改了一个' + name + '的名称！';
            },
            'Rename Prohibited': 'Rename Prohibited',
            'Cannot Rename for a Preset Content!': '无法更改预设内容的名称！',
            'Please Select Files To Be Upload.': '请选择要被上传的文件。',
            'Uploader Already In Working, Can Not Be Canceled!': '上传组件已经开始执行任务，无法取消操作',
            'Create Success': '创建成功',
            'A New Folder Has Been Create Successfully!': '成功新建了一个文件夹！',
            'Delete Success': '删除成功',
            'Delete_Success': function(name) {
                return '删除了一个' + name + '!';
            },
            'Delete_Success_Multiple': function(success) {
                return success + ' 个项目被已被删除!';
            },
            'Delete Failed!': '删除失败',
            'Items Readonly': '只读项目',
            'Please select items to be operate.': '请选择要被处理的项目。',
            'Uploader Already In Working, Please Wait For The End Of This Operation!': '上传组件已经开始执行任务，请等待本次任务完成！',
            'File_Count': function(count) {
                return count + ' 个文件被上传成功。';
            },
            'Waiting': '等待中',
            'Upload': '上传',
            'YangRAM Explorer Has Complete The Upload Operating!': 'YangRAM Explorer 已经完成了上传操作。',
            'But There Are Some Files Uploaded Failed.': '但是有些文件上传失败了。',
            'Upload Complete': '完成上传。',
            'Folder Not Exists': '未找到文件夹',
            '<Deleted>': function(name) {
                return name + '已经被删除' + '。';
            },
            '<ReadOnly>': function(name) {
                return name + '不能被操作' + '。[只读]';
            },
            '<CAN_NOT_FIND>': function(name) {
                return ' 未找到' + name;
            },
            'Move folders and files to': '移动文件夹和文件到'
        },
        ATTRS: {
            'MIME': 'MIME',
            'Width': '宽度',
            'Height': '高度',
        },
        NAMES: {
            'img': '图片',
            'txt': '文本',
            'vod': '视频',
            'wav': '音频',
            'folder': '文件夹',
            'doc': '文档'
        },
        MINI: {
            APPNAME: '迷你资源库',
            CLASSES: {
                'Preset Content': '预设内容',
                'Material Library': '素材文件',
                'Custom Content': '自定义内容'
            },
            OPERATIONS: {
                'Select All': '全选',
                'Unselect': '清除选择',
                'Insert Files': '插入文件',
                'Insert Contents': '关联内容'
            }
        }
    },

    TRASHCAN: {
        APPNAME: '废纸篓',
        MENUS: {
            'Create New Rule': '增加新规则',
            'Save Rule': '保存规则',
            'Recover Selected': '还原所选',
            'Recover Item': '还原该项',
            'Recover All': '还原全部',
            'Delete Selected': '删除所选',
            'Empty Item': '清空该项',
            'Empty All': '清空回收站',
        },
        WORDS: {
            'Recovery Success': '还原成功',
            'Recovery_Success': function(count) {
                return '还原了' + count + '个项目！';
            },
            'Recovery Failed!': '还原失败！',
            'Please select items to be recovery.': '请选择需要被还原的项目。',
            'A Item Has Been recovered!': '还原了一个项目！',
            'Item Not In Trash Can': '项目不在废纸篓中',
            'Delete Success': '删除成功',
            'Delete_Success': function(count) {
                return '彻底删除了' + count + '个项目！';
            },
            'Remove Failed!': '删除失败！',
            'A Item Has Been Completely Deleted!': '彻底删除了一个项目！',
            'Delete Failed Or Already Been Deleted': '已经被删除了',
        }
    },

    SETTINGS: {
        APPNAME: '控制面板',
    }
};