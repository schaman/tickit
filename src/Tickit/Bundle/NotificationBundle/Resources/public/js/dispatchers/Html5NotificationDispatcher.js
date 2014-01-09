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

    function Html5NotificationDispatcher() {
        if (Notification.permission !== 'denied') {
            Notification.requestPermission(function(perm) {
                if (!("permission" in Notification)) {
                    Notification.permission = perm;
                }
            });
        }

        return {
            dispatch : function(notifications) {
                if (Notification.permission !== 'granted') {
                    return;
                }

                if (!_.isArray(notifications)) {
                    notifications = [notifications];
                }

                _.each(notifications, function(n) {
                    var msg = new Notification('Tickit Notification', {
                        dir: 'auto',
                        tag: n.get('id'),
                        body: n.get('message')
                    });
                    msg.onclick = function() {
                        alert('marked as read (id: ' + n.get('id') + ')');
                    }
                });
            }
        }
    }

    _.extend(Html5NotificationDispatcher, AbstractNotificationDispatcher);

    return Html5NotificationDispatcher;
});
