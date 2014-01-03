/**
 * Pagination view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'text!paging/views/PaginationView.html'], function(Backbone, tpl) {
    return Backbone.View.extend({

        /**
         * Event bindings for this
         */
        events : {
            "click a" : "click"
        },

        /**
         * Renders the paging view
         */
        render : function() {
            // todo: this needs to render with the correct pages (potentially just inject values as params)
            this.$el.html(_.template($(tpl).html()));

            return this;
        },

        /**
         * Handles a click event on the paging navigation
         *
         * @param {object} e The click event object
         *
         * @returns {void}
         */
        click : function(e) {
            this.trigger('click', e);
        }
    });
});
