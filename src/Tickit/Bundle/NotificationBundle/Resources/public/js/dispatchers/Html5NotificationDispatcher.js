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
        dispatch : function(notifications) {
            if (!_.isArray(notifications)) {
                notifications = [notifications];
            }

            _.each(notifications, function(n) {
                var msg = new Notification('Tickit Notification', n.get('message'));
                msg.onclick = function() {
                    alert('marked as read (id: ' + n.get('id') + ')');
                }
            });
        }
    };

    _.extend(Html5NotificationDispatcher, AbstractNotificationDispatcher);

    return Html5NotificationDispatcher;
});
