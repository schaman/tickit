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
             * @param {object} vent     An event dispatcher
             *
             * @return {object}
             */
            factory : function(vent) {
                switch (true) {
                    case (Modernizr.notification):
                        return new Html5NotificationDispatcher({ vent: vent });
                        break;
                    default:
                        return new AmbientNotificationDispatcher({ vent: vent });
                }
            }
        };
    }

    return new NotificationDispatcherFactory;
});