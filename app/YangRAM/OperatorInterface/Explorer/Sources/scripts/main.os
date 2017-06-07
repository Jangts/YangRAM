use <Sources/scripts/preview>;
use <Sources/scripts/constant>;
use <Sources/scripts/static_p>;
use <Sources/scripts/static_m>;
use <Sources/scripts/static_event_h>;

public {
    name: __LANG__.APPNAME,
    listType: 'tile',
    listOrder: 'na',
    currentFolder: 6,
    chosenFolder: 6,
    foldersList: ''
};

private {
    palyer: null,
    toBeupload: [],
    toBeuploadNum:  0,
    uploadedNum: 0,
    uploadedFailNum: 0,
    uploadingState: false,
    uploading: false,
    uploaderScrollBAR: null
};

use <Sources/scripts/public_h>;
use <Sources/scripts/public_m>;