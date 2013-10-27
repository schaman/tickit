/**
 * Navigation item collection.
 *
 * Represents a collection of navigation items that make up a navigation area.
 *
 * @type {Backbone.Collection}
 */
define(['../../../../../../../.', 'navigation/js/models/NavigationItem'], function(Backbone, NavigationItem) {
    return Backbone.Collection.extend({
        model: NavigationItem,
        url: function() {
            return Routing.generate('api_navigation_items')
        }
    });
});
