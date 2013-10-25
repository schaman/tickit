/**
 * Notification module
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define([
    'modules/user',
    'notification/js/views/NotificationListView',
    'notification/js/collections/NotificationCollection'
], function(User, NotificationListView, NotificationCollection) {
    return App.module('Notification', function(module) {

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

                App.notificationRegion.show(view);
            });
        }
    });
});
