/**
 * Notification dispatcher factory.
 *
 * Responsible for creating a dispatcher factory that best
 * suits the browser's capabilities.
 */
define([
    'notification/js/dispatchers/Html5NotificationDispatcher',
    'notification/js/dispatchers/AmbientNotificationDispatcher'
], function(Html5NotificationDispatcher, AmbientNotificationDispatcher) {
    function NotificationDispatcherFactory() {
        return {

            /**
             * Factory method that creates a NotificationDispatcher.
             *
             * @return {object}
             */
            factory : function() {
                switch (true) {
                    case (Modernizr.notification):
                        return new Html5NotificationDispatcher();
                        break;
                    default:
                        return new AmbientNotificationDispatcher();
                }
            }
        };
    }

    return new NotificationDispatcherFactory();
});