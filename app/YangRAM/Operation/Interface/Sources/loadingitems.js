System.Loader(function(YangRAM, global, undefined) {
    var Runtime = this.Runtime,
        Promise = YangRAM.API.util.Promise,
        items = [
            /* Load HiBar Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/HiBarItems.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Title Bar And Context Menu Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/MenuInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Kalendar Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/KalendarEvents.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Smartian Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/SmartianStyle.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Launcher Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/LaunchInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load App Ranking List Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/ARLInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load App Bookmarks Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/BookmarkTypes.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Messager Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/MessageCenter.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Appliction Style Sheets / Step One */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/WinInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Appliction Style Sheets / Step Two */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/WLMInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Browser Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/BrowserInterface.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load I4Plaza Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/I4Plaza.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Settings Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/Settings.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Animations Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/styles/Animations.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Time Picker Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/themes/' + this.Theme + '/LAYOUT.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Color Picker Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/themes/' + this.Theme + '/COMPONENTS.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Option Selector Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/themes/' + this.Theme + '/HOMEPAGE.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Percentager Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/themes/' + this.Theme + '/MSGEVENTS.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Percentager Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + 'Sources/themes/' + this.Theme + '/SMARTIAN.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Account Informations */
            (resolve) => {
                this.UpdateLoadingStatus().GET({
                    url: YangRAM.RequestDIR + 'i/account/info/' + Runtime.locales.CODE,
                    done: (txt) => {
                        this.HiBar.build();
                        this.HiBar.Account.innerHTML = txt;
                        this.LoadedRateChange();
                        resolve();
                    },
                    fail: (txt) => {
                        console.log(txt);
                    }
                });
            },

            /* Load Kalendar Data */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Kalendar.off().load(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Smartian Configurations */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Smartian.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Launcher Style Sheets */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Workspace.Launcher.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Installed Application List */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Workspace.Launcher.Memowall.refresh(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load Unread Messages */
            (resolve) => {
                this.UpdateLoadingStatus();
                this.Notifier.build(() => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step One */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/Layout.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Two */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/TabViews.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Three */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/Slider.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* XX I: Load OIML Style Sheets / Step Four */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/SliderPanel.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Five */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/Menu.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Six */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/Blocks.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Seven */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/List.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Eight */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/Form.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Nine */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/RadioAndCheckBox.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Ten */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/EditPanel.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Eleven */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/ModernTable.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Twelve */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/ModernForm.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            },

            /* Load OIML Style Sheets / Step Thirteen */
            (resolve) => {
                this.UpdateLoadingStatus();
                var href = YangRAM.PhysicalURI + '../../ApplicationFramework/Sources/OIF/BgColors.css';
                YangRAM.loadStyle(href, () => {
                    this.LoadedRateChange();
                    resolve();
                });
            }
        ]

    this.Resize();
    this.ListenLoaded();
    YangRAM.API.isFn(this.onLoadingStatusChange) && this.onUpdateLoadingStatus();
    this.UpdateLoadingStatus();
    this.LoadedRateChange();

    Promise.oneByOne(items).then(() => {
        this.UpdateLoadingStatus();
        YangRAM.API.APP.launch('I4PLAZA');
    }, function() {
        console.log(this);
    });
}, true);