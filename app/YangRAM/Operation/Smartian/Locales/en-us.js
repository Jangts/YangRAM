System.Runtime.locales = {
    CODE: 'en-us',

    COMMON: {
        EMPTY_FN_TRIGGERED: 'You Have Triggered A Empty Function!',
        HELLO: 'Hello Yangram!',
        BYE_BYE: 'Bye-bye Yangram!',
        OF_NOT_FOUND: 'Operator File can not be found!',
        LAUNCH_SUCCESS: [
            'App Launch Successfully',
            function(name) {
                return 'Oh, Yes! The application [' + name + '] is launched, please enjoy it.';
            }
        ],
        AF_NOT_FOUND: 'Sorry, Cannot Load Main Function of this Application, Please Check Your Network or Reinstall this Application.',
        AP_NOT_FOUND: 'Sorry, Required Closure for this Application Not Found , Please Reinstall this Application.',
        MF_NOT_FOUND: 'Sorry, Main Function of this Application Is Not Defined, Please Reinstall this Application.',
        UNKNOWN_MISTAKE: 'Unknown Mistake',
        UM_TIPS: {
            title: 'Application Error',
            content: 'Something Wrong With This Appliction!',
            confirm: 'Close App',
            cancel: 'Ignore Err'
        },
        COPY: [
            'Copy Success',
            'Text has been successfully copied to the YangRAM clipboard.'
        ],
        ERR_APP_ID: 'Error Application Identification',
        CLOSE: {
            title_E: 'Application Still Working',
            content_E: function(name) {
                return name + ' is still working, are you sure to close this application or check it at first?';
            },
            resolve: "Close",
            reject: "Check",
            cancel: "Cancel",
            title_N: 'Close Application',
            content_N: 'Are you sure to close this application?',
        },
        TOPMENUS: {
            'Common': 'Common',
            'Operate': 'Operate',
            'New': 'New',
            'Create': 'Create',
            'Open': 'Open',
            'Edit': 'Edit',
            'Modifies': 'Modifies',
            'Save': 'Save',
            'Remove': 'Remove',
            'Delete': 'Delete',
            'Recover': 'Recover',

            'List': 'List',
            'Search': 'Search',
            'Seek': 'Seek',
            'Check': 'Check',

            'View': 'Pageview',
            'Pageview': 'Pageview',
            'Center': 'Center',
            'Cover': 'Cover',
            'Sleep': 'Sleep',
            'Close': 'Close',

            'Formats': 'Formats',
            'Plug-ins': 'Plug-ins',
            'Orders': 'Orders',
            'Settings': 'Settings',
            'Help': 'Help',
        },
        WORDS: {
            'No Permissions!': 'No Permissions!',
            'Something Wrong!': 'Something Wrong!',
            'Network Error!': 'Network Error!',
            'Unkonw Error!': 'Unkonw Error!',
            'Operate Failed': 'Operate Failed',
            'Cancel': 'Cancel',
            'Complete': 'Complete',
            'Confirm': 'Confirm',
            'No Insert Area': 'No Insert Area'
        },
        ATTRS: {
            'Name': 'Name',
            'Status': 'Status',
            'FileSize': 'FileSize',
            'Create Time': 'Create Time',
            'Modify Time': 'Modify Time',
            'Operate': 'Operate',
        }
    },

    UOI: {
        WELCOME_T: 'Welcome To Use.',
        WELCOME_C: function(time) {
            if (time > 1) {
                return 'This loading takes ' + time + ' milliseconds';
            } else {
                return 'This loading takes ' + time + ' millisecond';
            }
        }
    },

    LOGGER: {
        APPNAME: 'Logger',
        USERNAME: 'Username',
        PASSWORD: 'Password',
        USERNAME_CANNOT_EMPTY: 'Username Cannot Empty',
        PASSWORD_MUST_MORE_THEN: function(n) {
            return 'Password must be more than ' + n + ' digits';
        },
        LOG_CHECK_SUCCEED: 'Succeed!',
        USERNAME_OR_PASSWORD_NOT_MATCH: 'Incorrect username or password.',
        LOG_CHECK_FAIL: 'Verify failed, please try again later!',
        LOGOFF_CHECK: [
            ', etc. are still in the editing.\r\n',
            'Are you sure to continue your operation?'
        ],
    },

    LOCKER: {
        APPNAME: 'Locker',
        NOTICE: {
            title: 'Welcome Back.',
            content: function(time) {
                if (time > 1) {
                    return 'You\'ve been away for ' + parseFloat(time) + ' minutes';
                } else {
                    return 'You\'ve been away for ' + parseFloat(time) + ' minute';
                }
            }
        }
    },

    SYSTEM: {
        LOADSTATUS: [

            'Start Loading...',

            'Loading Style Sheets For HiBar Items...',
            'Loading Style Sheets For Menus...',
            'Loading Account Infomations...',

            'Loading Style Sheets For Kalendar...',
            'Loading Timer And Kalendar Events...',

            'Loading Style Sheets For Smartian',
            'Loading Configurations For Smartian...',

            'Loading Style Sheets For Launcher...',
            'Loading Style Sheets For Applictions Dock...',
            'Loading Style Sheets For Bookmarks...',
            'Loading Memowall Bookmarks Data...',
            'Loading Installed Application List...',

            'Loading Style Sheets For Notifier...',
            'Loading Unread Notices And Messages...',

            'Loading Style Sheets For Browser...',

            'Loading Style Sheets For Time Picker...',
            'Loading Style Sheets For Color Picker...',
            'Loading Style Sheets For Option Selector...',
            'Loading Style Sheets For Percentager...',

            'Loading Style Sheets For Applications Workspace...',
            'Loading Style Sheets For App In List Mode...',

            'Loading Style Sheets For Animations...',

            'Loading OIFramework / Common Layout Styles...',
            'Loading OIFramework / Common Backgroud Colors...',

            'Loading OIFramework / OIML Tabs Styles...',
            'Loading OIFramework / OIML Slider Styles...',
            'Loading OIFramework / OIML Slider Panel Styles...',

            'Loading OIFramework / OIML Menu Styles...',
            'Loading OIFramework / OIML Blocks Styles...',
            'Loading OIFramework / OIML Paging List Styles...',

            'Loading OIFramework / OIML Form Styles...',
            'Loading OIFramework / OIML Radio And CheckBox Styles...',
            'Loading OIFramework / OIML Edit Panel Styles...',

            'Loading OIFramework / Modern Table Styles...',
            'Loading OIFramework / Modern Form Styles...',

            'Loading Style Sheets For I4Plaza...',
            'Loading Style Sheets For Settings...',
            'Final Step : Launching I4Plaza...'
        ]
    },

    HIGHBAR: {
        APPNAME: 'HiBar',
        MENUS: {
            'Smartian': 'Smartian',
            'Msgcenter': 'Message Center',
            'Kalendar': 'Kalendar',
            'Taskmgr': 'Process Bus',
            'Center': 'Center CurApp',
            'Cover': 'Cover CurApp',
            'Sleep': 'Sleep CurApp',
            'Close': 'Close CurApp',
            'Frontpage': 'Front Home Page',
            'Apps': 'All Apps',
            'LogOff': 'LogOff',
            'Lockscreen': 'Lock Screen',
            'Launcher': 'My Desktop',
            'Registry': 'Registry',
            'tQuery': 'tQuery',
        },
        TAM: {
            APPNAME: 'Title And Menu Bars',
        },
        CTX: {
            APPNAME: 'Context Menus',
        },
        TMR: {
            WEEKS: {
                SUNDAY: 'Sunday',
                MONDAY: 'Monday',
                TUESDAY: 'Tuesday',
                WEDNESDAY: 'Wednesday',
                THURSDAY: 'Thursday',
                FRIDAY: 'Friday',
                SATURDAY: 'Saturday'
            },
            DAWN: 'dawn',
            MORNIN: 'morning',
            NOON: 'noon',
            AFTNOON: 'afternoon',
            EVENIN: 'evening',
            NIGHT: 'night',
            WEE: 'wee',
            UNIT: {
                year: 'year',
                month: 'month',
                week: 'week',
                day: 'day(s)',
                d: 'd',
                hour: 'hour(s)',
                cnhour: 'two-hour period(s)',
                h: 'h',
                minute: 'minute(s)',
                m: 'm',
                second: 'second(s)',
                s: 's',
                msec: 'millisecond(s)',
                ms: 'ms'
            }
        },
    },

    SMARTIAN: {
        APPNAME: 'Smartian',
    },

    NOTIFIER: {
        APPNAME: 'Message Center',
        DEFAULT: {
            title: 'A Notice From YangRAM',
            content: 'Hello, welcome to using this YANGRAM DATA OPERATING SYSTEM.',
            confirm: 'Sure',
            resolve: 'Yes',
            reject: 'No',
            cancel: 'Cancel'
        }
    },

    PROCESSBUS: {
        APPNAME: 'Actived App Bus',
        TABLE_HEAD: {
            'App Name': 'App Name',
            'App Status': 'App Status',
            'Run Time': 'Run Time',
            'Operation': 'Operation'
        },
        STATUS_AND_OPERATE: {
            'Actived': 'Actived',
            'Running': 'Running',
            'UnKnow': 'UnKnow',
            'Sleeping': 'Sleeping',
            'CURRENT': 'CURRENT',
            'Logoff': 'Logoff',
            'Restart': 'Restart',
            'CutTo': 'CutTo',
            'Close': 'Close',
            'Show2D': 'Show2D',
            'Show3D': 'Show3D',
            'NoOperation': 'No Operation',
            'ReturnI4Plaza': 'Return I4Plaza'
        },
    },

    KALENDAR: {
        APPNAME: 'Kalendar',
    },

    MAGICCUBE: {
        APPNAME: 'Magic Animate Cube',
    },

    DIALOG: {
        APPNAME: 'Common Dialog',
    },

    UPLOADER: {
        APPNAME: 'Common Uploader',
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
            APPNAME: 'Memowall',
            MENUS: {
                'New Group': 'New Group',
                'Refresh': 'Refresh',
                'Edit Group': 'Edit Group',
                'Delete Group': 'Delete Group',
                'Editor Link': 'Editor Link',
                'Delete Link': 'Delete Link',
                'New Link': 'New Link',
                'Sorting': 'Sorting'
            }
        },
        ARL: {
            APPNAME: 'App Ranking List',
            MENUS: {
                'Launch': 'Launch',
                'Sleep': 'Sleep',
                'Awake': 'Awake',
                'Close': 'Close',
                'Uninstall': 'Uninstall',
                'On/Off': 'On/Off',
                'New Group': 'New Group',
                'Basic/General': 'Basic/General',
                'Channel/Columns/Router': 'Channel/Columns/Router',
                'Content Presets': 'Content Presets',
                'Applications/Themes': 'Applications/Themes',
                'Driver/Protocol': 'Driver/Protocol',
                'Operators Manage': 'Operators Manage',
                'Personal': 'Personal',
                'Agency/Languges': 'Agency/Languges',
                'Update/Safety': 'Update/Safety',
                'New Recycle Rlue': 'New Recycle Rlue',
                'Empty Recycling Items': 'Empty Recycling Items',
                'Preset Contents': 'Preset Contents',
                'Resources Library': 'Resources Library',
                'Pictures': 'Picture',
                'Documents': 'Documents',
                'Texts': 'Text',
                'Compressed Files': 'Compressed Files',
                'Videos': 'Videos',
                'Musics': 'Musics',
                'Other Archives': 'Other Archives'
            }
        },
        MGR: {
            APPNAME: 'Bookmark Manager'
        }
    },

    I4PLAZA: {
        APPNAME: 'I4Plaza',
        MENUS: {
            'System Settings': 'System Settings',
            'App Store': 'App Store',
            'Process Bus': 'Process Bus',
            'Use Manual': 'Use Manual',
            'To Do List': 'To Do List',
            'System Update': 'System Update',
            'YangRAM Official Website': 'YangRAM Official Website',
            'Forum': 'Forum',
            'Developer Center': 'Developer Center',
            'About YangRAM': 'About YangRAM'
        }
    },

    EXPLORER: {
        APPNAME: 'YangRAM Explorer',
        MENUS: {
            'Default Page': 'Default Page',
            'Refresh': 'Refresh',
            'Open Folder': 'Open Folder',
            'Move To': 'Move To',
            'Rename': 'Rename',
            'Delete Folder': 'Delete Folder',
            'Delete Content': 'Delete Content',
            'Copy URL': 'Copy URL',
            'Replace': 'Replace',
            'Delete Documents': 'Delete Documents',
            'Preview': 'Preview',
            'Copy Code': 'Copy Code',
            'Play': 'Play',
            'Copy HTML Code': 'Copy HTML Code'
        },
        WORDS: {
            'No Support': 'No Support',
            'May Timeout': 'May Timeout',
            'Different Formats!': 'Different Formats!',
            'Type Not Support!': 'Type Not Support!',
            'Filesize OVER!': 'Filesize OVER!',
            'No Legal File Selected!': 'No Legal File Selected!',
            'Copy URL': 'Copy URL',
            'natural size': 'natural size',
            'Copy HTML Code': 'Copy HTML Code',
            'Copy File URL': 'Copy File URL',
            'Can Not Contain <>/\|:"*?': 'Can Not Contain <>/\|:"*?',
            'Can Not More Than 50 Words!': 'Can Not More Than 50 Words!',
            'Can Not Empty!': 'Can Not Empty!',
            'File Not Exists!': 'File Not Exists!',
            'Rename Success': 'Rename Success!',
            'Rename_Success': function(name) {
                return 'A ' + name + ' Has Been Rename Successfully!';
            },
            'Rename Prohibited': 'Rename Prohibited',
            'Cannot Rename for a Preset Content!': 'Cannot Rename for a Preset Content!',
            'Please Select Files To Be Upload.': 'Please Select Files To Be Upload.',
            'Uploader Already In Working, Can Not Be Canceled!': 'Uploader Already In Working, Can Not Be Canceled!',
            'Create Success': 'Create Success',
            'A New Folder Has Been Create Successfully!': 'A New Folder Has Been Create Successfully!',
            'Delete Success': 'Delete Success',
            'Delete_Success': function(name) {
                return 'A ' + name + ' Has Been Deleted!';
            },
            'Delete_Success_Multiple': function(success) {
                return success + ' Items Deleted Completely!';
            },
            'Delete Failed!': 'Delete Failed!',
            'Items Readonly': 'Items Readonly',
            'Please select items to be operate.': 'Please select items to be operate.',
            'Uploader Already In Working, Please Wait For The End Of This Operation!': 'Uploader Already In Working, Please Wait For The End Of This Operation!',
            'File_Count': function(count) {
                if (count > 1) {
                    return count + ' Files To Be Uploaded.';
                } else {
                    return count + ' File To Be Uploaded.';
                }
            },
            'Waiting': 'Waiting',
            'Upload': 'Upload',
            'YangRAM Explorer Has Complete The Upload Operating!': 'YangRAM Explorer Has Complete The Upload Operating!',
            'But There Are Some Files Uploaded Failed.': 'But There Are Some Files Uploaded Failed.',
            'Upload Complete': 'Upload Complete',
            'Folder Not Exists': 'Folder Not Exists',
            '<Deleted>': function(name) {
                return name + ' Has Already Be Deleted.';
            },
            '<ReadOnly>': function(name) {
                return name + ' Can Not Be Operated. [READONLY]';
            },
            '<CAN_NOT_FIND>': function(name) {
                return name + ' Not Found';
            },
            'Move folders and files to': 'Move folders and files to',
        },
        ATTRS: {
            'MIME': 'MIME',
            'Width': 'Width',
            'Height': 'Height',
        },
        NAMES: {
            'img': 'Picture',
            'txt': 'Text',
            'vod': 'Video',
            'wav': 'Music',
            'folder': 'Folder',
            'doc': 'Document'
        },
        MINI: {
            APPNAME: 'Mini Explorer',
            CLASSES: {
                'Preset Content': 'Preset Content',
                'Resources Library': 'Resources Library',
                'Custom Content': 'Custom Content'
            },
            OPERATIONS: {
                'Select All': 'Select All',
                'Unselect': 'Unselect',
                'Insert Files': 'Insert Files',
                'Insert Contents': 'Insert Contents'
            }
        }
    },

    TRASHCAN: {
        APPNAME: 'Trash Can',
        MENUS: {
            'Create New Rule': 'Create New Rule',
            'Save Rule': 'Save Rule',
            'Recover Selected': 'Recover Selected',
            'Recover Item': 'Recover Item',
            'Recover All': 'Recover All',
            'Delete Selected': 'Delete Selected',
            'Empty Item': 'Empty Item',
            'Empty All': 'Empty All',
        },
        WORDS: {
            'Recovery Success': 'Recovery Success',
            'Recovery_Success': function(count) {
                return count + ' Items Recovered Completely!';
            },
            'Recovery Failed!': 'Recovery Failed!',
            'Please select items to be recovery.': 'Please select items to be recovery.',
            'A Item Has Been recovered!': 'A Item Has Been recovered!',
            'Item Not In Trash Can': 'Item Not In Trash Can',
            'Delete Success': 'Delete Success',
            'Delete_Success': function(count) {
                return count + ' Items Have Been Completely Deleted!';
            },
            'Remove Failed!': 'Remove Failed!',
            'A Item Has Been Completely Deleted!': 'A Item Has Been Completely Deleted!',
            'Delete Failed Or Already Been Deleted': 'Delete Failed Or Already Been Deleted',
        }
    },

    SETTINGS: {
        APPNAME: 'Settings',
    }
};