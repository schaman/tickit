/**
 * HTML 5 notification dispatcher.
 *
 * Responsible for dispatching new notifications via the HTML5
 * notification API.
 *
 * @type {object}
 */
define([
    'notification/js/dispatchers/AbstractNotificationDispatcher'
], function(AbstractNotificationDispatcher) {
    function Html5NotificationDispatcher(options) {
        options = options || {};
        console.log(options.provider);
    }

    // todo: add inheritance for AbstractNotificationDispatcher (potentially Node's util.inherits)

    return Html5NotificationDispatcher;
});
