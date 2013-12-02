/**
 * Filterable List View
 *
 * A base view object that all lists providing filter functionality
 * can extend/override.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define(function() {

    return Backbone.Marionette.CompositeView.extend({

        /**
         *
         */
        collection: null,

        /**
         *
         */
        filterViewPrototype: null,

        /**
         *
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
         * Fired after the view has been rendered
         */
        onRender : function() {
            // we instantiate a new instance of the prototype provided
            var filterView = new this.filterViewPrototype({
                formUrl: this.filterFormUrl,
                el : $('#filter')
            });

            this.collection.setFilterView(filterView);
        }
    });
});
