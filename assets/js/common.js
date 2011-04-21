/**
 * Shows a message box with given the title and message.
 * 
 * @param {} title
 * @param {} message
 */
var message = function(title, message, icon) {
    Ext.Msg.show({
        title: title,
        msg: message,
        minWidth: 200,
        modal: true,
        icon: Ext.Msg.ERROR,
        buttons: Ext.Msg.OK
    });
}

/**
 * Initialize tooltips.
 */
$(document).ready(function() {
    Ext.QuickTips.init();
});
