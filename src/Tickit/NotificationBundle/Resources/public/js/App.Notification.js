/**
 * Notification module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
define([
    'modules/user',
    'notification/js/views/NotificationListView',
    'notification/js/collections/NotificationCollection'
], function(User) {
    return App.module('Notification', function(module, NotificationListView, NotificationCollection) {

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

                App.navRegion.show(view);
            });
        }
    });
});
