/**
 * Notification module
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define([
    'modules/user',
    'notification/js/providers/PollingNotificationProvider',
    'notification/js/dispatchers/factory/NotificationDispatcherFactory',
    'notification/js/views/NotificationListView',
    'notification/js/collections/NotificationCollection'
], function(User, NotificationProvider, DispatcherFactory, NotificationListView, NotificationCollection) {
    return App.module('Notification', function(module) {

        /**
         * The notification provider
         *
         * @type {object}
         */
        module.provider = null;

        module.startWithParent = false;

        /**
         * Loads notifications for the current user
         *
         * @return {void}
         */
        module.loadNotifications = function() {
            User.loadCurrentUser(function() {
                var notifications = new NotificationCollection;
                notifications.fetch();
                var view = new NotificationListView({
                    collection: notifications
                });

                module.provider = new NotificationProvider({ collection: notifications });
                module.dispatcher = DispatcherFactory.factory(module.provider);

                App.notificationRegion.show(view);
            });
        }
    });
});
