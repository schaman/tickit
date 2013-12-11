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
         * The URL used for fetching project models
         *
         * @returns {string}
         */
        url: function() {
            return Routing.generate('api_project_list');
        }
    });
});
