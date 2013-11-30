/**
 * Filter view.
 *
 * Manages a set of filter controls on the page
 *
 * @type {Backbone.View}
 */
define(['modules/template'], function(Template) {

    return Backbone.View.extend({

        /**
         * Event bindings for the filter view
         */
        events : {
            "change": "dispatchFilterChange"
        },

        /**
         * Initialise the view
         */
        initialize : function() {
            if (null === this.el) {
                throw new Error('You must provide an "el" property to reference the element containing the filters');
            }
        },

        /**
         * Gets the current filter values
         */
        getFilterValues : function() {
            // TODO: iterate over this.el and return all current filter values
            return {};
        },

        /**
         * Dispatches the "filters:change" event via App.vent
         */
        dispatchFilterChange : function() {
            this.trigger('change', this.getFilterValues());
        }
    });
});
