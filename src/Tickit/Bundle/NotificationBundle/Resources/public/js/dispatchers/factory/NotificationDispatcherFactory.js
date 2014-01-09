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
             * @param {object} provider A notification provider
             *
             * @return {AbstractNotificationDispatcher}
             */
            factory : function(provider) {

                // todo: add browser detection here to determine which dispatcher to use

                return new Html5NotificationDispatcher({ provider: provider });
            }
        };
    }

    return new NotificationDispatcherFactory;
});