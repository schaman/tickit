/**
 * Notification model.
 *
 * Represents a notification for a user
 *
 * @type {Backbone.Model}
 */
define(['../../../../../../../.'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            id: null,
            message: '',
            actionUri: '',
            createdAt: new Date()
        },

        /**
         * Gets the createdAt datetime in a friendly format
         *
         * @return {string}
         */
        getCreatedAt : function() {
            var date = this.get('createdAt');
            if (typeof date != 'object') {
                date = new Date(date);
            }
            return date.toLocaleString();
        }
    });
});
