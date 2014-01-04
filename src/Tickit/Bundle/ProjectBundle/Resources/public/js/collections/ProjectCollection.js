/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
define([
    'filter/js/collections/FilterableCollection',
    'project/js/models/Project'
], function(FilterableCollection, Project) {
    return FilterableCollection.extend({

        /**
         * The model type that this collection manages
         *
         * @type {Backbone.Model}
         */
        model: Project,

        /**
         * Gets the route name for this collection data
         *
         * @return {string}
         */
        getRouteName : function() {
            return 'api_project_list';
        }
    });
});
