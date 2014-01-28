/**
 * Navigation item collection.
 *
 * Represents a collection of navigation items that make up a navigation area.
 *
 * @type {Backbone.Collection}
 */
define(['backbone', 'navigation/js/models/NavigationItem'], function(Backbone, NavigationItem) {
    return Backbone.Collection.extend({
        model: NavigationItem,

        /**
         * The name of the navigation that this collection represents
         *
         * @type {string}
         */
        name: null,

        /**
         * Gets the URL used to fetch content for this collection
         *
         * @return {string}
         */
        url: function() {
            var params = {};
            if (this.name !== null) {
                params.name = this.name;
            }
            return Routing.generate('api_navigation_items', params);
        },

        /**
         * Initializes the collection.
         *
         * @param {Array}  models  An array of models to initialize the collection with
         * @param {object} options An options object (optional)
         */
        initialize : function(models, options) {
            options = options || {};
            if (typeof options.name !== 'undefined') {
                this.name = options.name;
            }
        }
    });
});
