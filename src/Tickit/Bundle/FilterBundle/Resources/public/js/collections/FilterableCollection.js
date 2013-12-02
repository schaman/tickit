/**
 * Filterable Collection.
 *
 * @type {Backbone.Collection}
 */
define(function() {
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
            if (!options) {
                return;
            }

            if (options.filterView) {
                this.setFilterView(options.filterView);
            }

            if (options.paginationView) {
                this.setPaginationView(options.paginationView);
            }
        },

        /**
         * Sets the filter view on this collection.
         *
         * Internally, it also binds to the "change" event on the filter
         * view so that changes to the filters reflect in the collection.
         *
         * @param {Backbone.View} filterView The filter view
         */
        setFilterView : function(filterView) {
            this.filterView = filterView;
            this.filterView.on('change', this.updateFromValues);
        },

        /**
         * Sets the pagination view on this collection
         *
         * @param {Backbone.View} paginationView The pagination view
         */
        setPaginationView : function(paginationView) {
            // TODO: bind to the change event on the pagination view (needs to be created)
            this.paginationView = options.paginationView;
        },

        /**
         * Updates this collection from a set of filter values.
         *
         * The filter values should be key => value pairs
         *
         * @param {object} values The filter values to update from
         */
        updateFromValues : function(values) {
            this.fetch({
                reset: true,
                data: values
            });
        }
    });
});