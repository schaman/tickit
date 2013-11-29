/**
 * Filterable Collection.
 *
 * @type {Backbone.Collection}
 */
define(['modules/app'], function(App) {
    return Backbone.Collection.extend({

        /**
         * The filter view associated with this collection.
         *
         * This view manages a collection of form controls that are
         * used to filter this collection.
         *
         * @type {Backbone.View}
         */
        filterView : null,

        /**
         * The pagination view associated with this collection.
         *
         * This view manages the pagination controls used to navigate
         * through this collection.
         *
         * @type {Backbone.View}
         */
        paginationView : null,

        /**
         * Initialises the collection
         *
         * @param {object} options The initialization options for the collection
         */
        initialize : function(options) {
            if (!options.filterView) {
                throw new Error('You must provide a valid filterView option to the FilterableCollection');
            }

            if (options.paginationView) {
                this.paginationView = options.paginationView;
            }

            this.filterView = options.filterView;
            this.filterView.on('filters:change', this.updateFromValues);
        },

        /**
         * Updates this collection from a set of filter values.
         *
         * The filter values should be key => value pairs
         *
         * @param {object} values The filter values to update from
         */
        updateFromValues : function(values) {
            // TODO: update this collection with the filter values

            this.fetch({
                reset: true,
                data: {}
            });
        }
    });
});