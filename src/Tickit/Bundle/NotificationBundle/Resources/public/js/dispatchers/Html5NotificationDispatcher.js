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

    function Html5NotificationDispatcher() {}
    Html5NotificationDispatcher.prototype = {
        // todo
    };

    _.extend(Html5NotificationDispatcher, AbstractNotificationDispatcher);

    return Html5NotificationDispatcher;
});
