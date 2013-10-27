/**
 * Navigation item model.
 *
 * Represents an item in the navigation
 *
 * @type {Backbone.Model}
 */
define(['../../../../../../../.'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            name: '',
            routeName: '',
            active: true
        },

        /**
         * Gets the URI for this item based off its route name
         *
         * @return {string}
         */
        getUri: function() {
            return Routing.generate(this.get('routeName'));
        }
    });
});
