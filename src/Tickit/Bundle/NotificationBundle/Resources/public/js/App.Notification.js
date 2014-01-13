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
    'notification/js/collections/NotificationCollection',
    'backbone'
], function(User, NotificationProvider, DispatcherFactory, NotificationListView, NotificationCollection, Backbone) {
    return App.module('Notification', function(module) {

        /**
         * The notification provider
         *
         * @type {object}
         */
        module.provider = null;

        module.startWithParent = false;

        /**
         * Event dispatcher for coordinating notification events between components
         *
         * @type {Backbone.Events}
         */
        module.eventDispatcher = _.extend({}, Backbone.Events);

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
                    collection: notifications,
                    vent: module.eventDispatcher
                });

                module.provider = new NotificationProvider({ collection: notifications, vent: module.eventDispatcher });
                module.dispatcher = DispatcherFactory.factory(module.provider, module.eventDispatcher);

                App.notificationRegion.show(view);
            });
        };
    });
});
