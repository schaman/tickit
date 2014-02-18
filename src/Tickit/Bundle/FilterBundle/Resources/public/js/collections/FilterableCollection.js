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
            totalPages: "totalPages"
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
            this.filterView = filterView;
            this.listenTo(this.filterView, 'change', function(values) {
                this.updateFromValues(values, this);
            });
        },

        /**
         * Sets the pagination view on this collection
         *
         * @param {Backbone.View} paginationView The pagination view
         */
        setPaginationView : function(paginationView) {
            this.paginationView = paginationView;

            this.listenTo(this.paginationView, 'pagechange', function(pageNumber, callBack) {
                var state = this.state;
                if (isNaN(pageNumber)) {
                    switch(pageNumber) {
                        case 'next':
                            pageNumber = state.currentPage + 1;
                            if (pageNumber > state.lastPage) {
                                pageNumber = state.lastPage;
                            }
                            break;
                        case 'prev':
                            pageNumber = state.currentPage - 1;
                            if (pageNumber < state.firstPage) {
                                pageNumber = state.firstPage;
                            }
                            break;
                        default:
                            return;
                    }
                }

                this.getPage(pageNumber, {
                    success: function() {
                        if (typeof callBack == 'function') {
                            callBack(pageNumber);
                        }
                    }
                });
            });
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
                    page: resp.currentPage,
                    totalPages: resp.pages
                },
                resp.data
            ];

            this.state.lastPage = resp.pages;
            this.paginationView.render(resp.pages);

            return BackbonePageable.prototype.parse.apply(this, [state]);
        },

        /**
         * Generates the URL for this collection.
         *
         * Relies on the extending object to provide the route name via
         * a getRouteName() method.
         *
         * @returns {string}
         */
        url: function() {
            var page = this.state && this.state.currentPage && Number(this.state.currentPage) > 0 ? this.state.currentPage : 1;
            var filters = this.filterView !== null ? this.filterView.getFilterValues() : {};
            return Routing.generate(this.getRouteName(), _.extend({ page: page }, filters));
        }
    });
});