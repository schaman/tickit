/**
 * Filter view.
 *
 * Manages a set of filter controls on the page
 *
 * @type {Backbone.View}
 */
define(['modules/template', 'select2'], function(Template) {

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
                t.initSelect2.apply(t);
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
        },

        /**
         * Initialises the select2 integration
         */
        initSelect2 : function() {
            var $pickers = this.$el.find('.picker');
            _.each($pickers, function(el) {
                var $e = $(el);
                var maxSelections = $e.data('max-selections') || false;
                var options = {
                    multiple: true,
                    minimumInputLength: 3,
                    query: function(query) {
                        if (query.term.length  < 3) {
                            query.callback({ results: [] });

                            return;
                        }

                        // this will be substituted with an ajax request
                        setTimeout(function () {
                            query.callback({
                                results: [
                                    { id: 1, text: "Mark Wilson" },
                                    { id: 2, text: "James Halsall" },
                                    { id: 3, text: "Stuart Rayson" }
                                ]
                            });
                        }, 500);
                    }
                };
                if (maxSelections !== false) {
                    options.maximumSelectionSize = maxSelections;
                }
                $e.select2(options);
            });
        }
    });
});
