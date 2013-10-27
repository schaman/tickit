/**
 * Notification collection.
 *
 * Represents a collection of notifications for the current user.
 *
 * @type {Backbone.Collection}
 */
define(['backbone', 'notification/js/models/Notification'], function(Backbone, Notification) {
    return Backbone.Collection.extend({
        model: Notification,
        url: function() {
            return Routing.generate('api_notification_list')
        }
    });
});
