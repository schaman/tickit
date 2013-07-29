/**
 * Notification model.
 *
 * Represents a notification for a user
 *
 * @type {Backbone.Model}
 */
define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            id: null,
            message: '',
            actionUri: '',
            createdAt: new Date()
        }
    });
});
