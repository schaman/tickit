/**
 * Filterable Collection.
 *
 * @type {Backbone.Collection}
 */
define(['backbone/pageable'], function(BackbonePageable) {
    return BackbonePageable.extend({

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
         * The initial state of the pageable collection
         */
        state : {
            firstPage: 1,
            currentPage: 1
        },

        queryParams : {
            currentPage: "page"
        },

        /**
         * Initialises the collection
         *
         * @param {object} options The initialization options for the collection
         */
        initialize : function(options) {
            options = options || {};

            if (options.filterView) {
                this.setFilterView(options.filterView);
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
            var t = this;
            t.filterView = filterView;
            t.filterView.on('change', function(values) {
                t.updateFromValues(values, t);
            });
        },

        /**
         * Sets the pagination view on this collection
         *
         * @param {Backbone.View} paginationView The pagination view
         */
        setPaginationView : function(paginationView) {
            this.paginationView = paginationView;

            this.listenTo(this.paginationView, 'click', function(pageNumber) {
                this.getPage(pageNumber);
            });
        },

        /**
         * Updates this collection from a set of filter values.
         *
         * The filter values should be key => value pairs
         *
         * @param {object} values The filter values to update from
         * @param {object} scope  The scope object to call fetch on (should be this instance)
         */
        updateFromValues : function(values, scope) {
            scope.fetch({
                reset: true,
                data: values
            });
        },

        /**
         * Parses the response data from the server
         *
         * @param {object} resp The decoded response object
         *
         * @returns {Array}
         */
        parse : function(resp) {
            var state = [
                {
                    totalRecords: resp.total,
                    currentPage: resp.currentPage,
                    totalPages: resp.pages
                },
                resp.data
            ];

            this.paginationView.render(resp.pages);

            return BackbonePageable.prototype.parse.apply(this, [state]);
        }
    });
});