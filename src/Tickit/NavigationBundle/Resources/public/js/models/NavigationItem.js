/**
 * Navigation item model.
 *
 * Represents an item in the navigation
 *
 * @type {Backbone.Model}
 */
define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            name: '',
            uri: '',
            active: true
        }
    });
});
