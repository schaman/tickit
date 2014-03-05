/**
 * Issue collection.
 *
 * Manages a collection of Issue instances.
 *
 * @type {Backbone.Collection.extend}
 */
define([
    'filter/js/collections/FilterableCollection',
    'issue/js/models/Issue'
], function(FilterableCollection, Issue) {
    return FilterableCollection.extend({

        /**
         * The model type that this collection manages
         *
         * @type {Backbone.Model}
         */
        model: Issue,

        /**
         * Gets the route name for this collection data
         *
         * @return {string}
         */
        getRouteName : function() {
            return 'api_issue_list';
        }
    });
});
