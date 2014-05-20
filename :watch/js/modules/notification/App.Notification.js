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
                var notifications = new NotificationCollection();
                notifications.fetch();
                var view = new NotificationListView({ collection: notifications });

                module.provider = new NotificationProvider({ collection: notifications });
                module.dispatcher = DispatcherFactory.factory();

                App.notificationRegion.show(view);
            });
        };

        // because this module isn't loaded as part of the application core
        // (see App module) we want to bind to navigation:ready event immediately,
        // even before this module has fully initialised
        App.vent.on('navigation:ready', function($el) {
            $el.find('#notification').sidr({
                name: 'notification-side',
                side: 'right'
            });
        });
    });
});
