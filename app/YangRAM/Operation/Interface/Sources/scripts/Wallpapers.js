System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Workspace = System.Workspace,
        _ = System.Pandora;


    _.extend(Workspace, true, {

        listenEvents() {
            return this;
        }
    }).build();
});