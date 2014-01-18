/**
 * Notification collection.
 *
 * Represents a collection of notifications for the current user.
 *
 * @type {Backbone.Collection}
 */
define(['backbone', 'notification/js/models/Notification', 'underscore'], function(Backbone, Notification, _) {
    return Backbone.Collection.extend({
        model: Notification,

        /**
         * Initializes the collection
         */
        initialize : function() {
            App.Notification.vent.on('notification', this.addToCollection, this);
        },

        /**
         * Adds items to the collection
         *
         * @param {Array} notifications An array of notifications to add to the collection
         */
        addToCollection : function(notifications) {
            var self = this;
            _.each(notifications, function(n) {
                var existing = self.get(n.get('id'));
                if (existing) {
                    self.remove(existing);
                }

                self.add(n);
            });
        },

        /**
         * Builds the URL for this collection
         *
         * @returns {string}
         */
        url: function() {
            return Routing.generate('api_notification_list')
        }
    });
});
