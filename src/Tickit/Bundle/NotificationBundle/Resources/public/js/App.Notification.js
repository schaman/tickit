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
    'backbone',
    'underscore'
], function(User, NotificationProvider, DispatcherFactory, NotificationListView, NotificationCollection, Backbone, _) {
    return App.module('Notification', function(module) {

        module.startWithParent = false;

        /**
         * The notification provider
         *
         * @type {object}
         */
        module.provider = null;

        /**
         * An event aggregator for everything notification related
         *
         * @type {EventAggregator}
         */
        module.vent = _.extend({}, Backbone.Events);

        /**
         * Loads notifications for the current user
         *
         * @return {void}
         */
        module.loadNotifications = function() {
            User.loadCurrentUser(function() {
                var notifications = new NotificationCollection;
                notifications.fetch();
                var view = new NotificationListView({ collection: notifications });

                module.provider = new NotificationProvider({ collection: notifications });
                module.dispatcher = DispatcherFactory.factory();

                App.notificationRegion.show(view);
            });
        };
    });
});
