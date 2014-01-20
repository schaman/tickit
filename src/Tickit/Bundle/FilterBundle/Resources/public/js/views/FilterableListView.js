/**
 * Filterable List View
 *
 * A base view object that all lists providing filter functionality
 * can extend/override.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define(['paging/js/views/PaginationView'], function(PaginationView) {

    return Backbone.Marionette.CompositeView.extend({

        /**
         * The collection that is being filtered by this view
         *
         * @type {Backbone.Collection}
         */
        collection: null,

        /**
         * The FilterView prototype that will be instantiated to manage
         * the filters.
         *
         * @type {Backbone.View}
         */
        filterViewPrototype: null,

        /**
         * The URL used to fetch the contents of the filter form
         *
         * @type {string}
         */
        filterFormUrl : '',

        /**
         * Initialises the view
         *
         * @param {object} options The view options
         */
        initialize : function(options) {
            if (!options.collection) {
                throw new Error('You must provide a "collection" option to the filterable list view');
            }

            if (!options.filterViewPrototype) {
                throw new Error('You must provide a "filterViewPrototype" option to the filterable list view');
            }

            if (!options.filterFormUrl || (typeof options.filterFormUrl != 'string')) {
                throw new Error('You must provide a "filterFormUrl" option to the filterable view');
            }

            this.filterFormUrl = options.filterFormUrl;
            this.filterViewPrototype = options.filterViewPrototype;
            this.collection = options.collection;
        },

        /**
         * Fired after the view has been rendered.
         *
         * This method triggers the creation of the filter and pagination views.
         *
         * @return {void}
         */
        onShow : function() {
            // we instantiate a new instance of the prototype provided
            var filterView = new this.filterViewPrototype({
                formUrl: this.filterFormUrl,
                el : 'div.filter'
            });

            this.collection.setFilterView(filterView);
            this.collection.setPaginationView(new PaginationView);
        }
    });
});
