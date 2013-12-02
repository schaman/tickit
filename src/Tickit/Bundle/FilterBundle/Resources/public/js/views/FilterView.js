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
        initialize : function(options) {
            if (!options.formUrl) {
                throw new Error('You must provide a "formUrl" property where the filter form content will be fetched from');
            }

            if (null === options.el) {
                throw new Error('You must provide an "el" property to reference the filters container');
            }

            this.fetchFilterForm(options.formUrl);
        },

        /**
         * Fetches the filter form content from a source URL
         *
         * @param {String} url The URL where we can fetch the filters from
         */
        fetchFilterForm : function(url) {
            var t = this;
            Template.fetch(url, function(tpl) {
                t.$el.html(tpl);
            });
        },

        /**
         * Gets the current filter values.
         *
         * Will return an object where the field names are object
         * properties, with their corresponding values.
         *
         * @return {object}
         */
        getFilterValues : function() {
            var values = this.$el.find('form').serializeArray();
            var flat = {};
            _.each(values, function(field) {
                flat[field.name] = field.value;
            });

            return flat;
        },

        /**
         * Dispatches the "change" event on this instance
         */
        dispatchFilterChange : function() {
            this.trigger('change', this.getFilterValues());
        }
    });
});
