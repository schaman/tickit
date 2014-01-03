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
         *
         * @param {Number} totalPages The total number of pages
         *
         * @return {Backbone.View}
         */
        render : function(totalPages) {
            if (totalPages < 2) {
                this.$el.html('');
                return this;
            }

            this.$el.html(_.template($(tpl).html(), {
                totalPages: totalPages
            }));

            return this;
        },

        /**
         * Handles a click event on the paging navigation
         *
         * @param {object} e The click event object
         *
         * @returns {void}
         */
        click : function() {
            this.trigger('click', $(this).data('page'));
        }
    });
});
