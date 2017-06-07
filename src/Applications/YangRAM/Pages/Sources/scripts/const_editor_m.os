const CHK_FORMDATA(data) {
    var elem = __thisapp__.$('section[data-tab-name].curr popup-form form-vision')[0];
    var form = new YangRAM.API.form.Data(elem);
    var fields = form.checkValue().data;
    for (var i in fields) {
        data.append(i, fields[i][0]);
    }
    return data;
};