/**
 * Shows a message box with given the title and message.
 * 
 * @param {} title
 * @param {} message
 * @param {} icon
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
};

/**
 * Logs the user out.
 */
var logout = function() {
    Ext.Ajax.request({
        url: BASE_URL + 'auth/logout',
        method: 'post',
        success: function(xhr) {
            window.location = BASE_URL + 'auth/login';
        }
    });
};

/**
 * Application main entry point
 */
Ext.onReady(function() {
    Ext.QuickTips.init();

    if (typeof(SettingsWindow) == "function") {
    	settings = new SettingsWindow();
    }
});
