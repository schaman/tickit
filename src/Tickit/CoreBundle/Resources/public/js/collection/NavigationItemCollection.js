/**
 * Navigation item collection.
 *
 * Represents a collection of navigation items that make up a navigation area.
 *
 * @type {Backbone.Collection}
 */
define(['backbone', 'tickitcore/js/models/NavigationItem'], function(Backbone, NavigationItem) {
    return Backbone.Collection.extend({
        mode: NavigationItem,
        url: '/api/navigation'
    });
});