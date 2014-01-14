/**
 * Notification dispatcher factory.
 *
 * Responsible for creating a dispatcher factory that best
 * suits the browser's capabilities.
 */
define([
    'notification/js/dispatchers/Html5NotificationDispatcher'
], function(Html5NotificationDispatcher) {
    function NotificationDispatcherFactory() {
        return {

            /**
             * Factory method that creates a NotificationDispatcher.
             *
             * @param {object} vent     An event dispatcher
             *
             * @return {AbstractNotificationDispatcher}
             */
            factory : function(vent) {

                switch (true) {
                    case (Modernizr.notification):
                        return new Html5NotificationDispatcher({ vent: vent });
                        break;
                }

                return null;
            }
        };
    }

    return new NotificationDispatcherFactory;
});