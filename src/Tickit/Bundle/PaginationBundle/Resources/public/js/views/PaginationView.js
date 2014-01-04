/**
 * Pagination view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'text!paging/views/PaginationView.html'], function(Backbone, tpl) {
    return Backbone.View.extend({

        el: 'div.list-pagination',

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
            } else {
                this.$el.html(_.template($(tpl).html(), {
                    totalPages: totalPages
                }));

                this.setActivePage(1);
            }

            return this;
        },

        /**
         * Handles a click event on the paging navigation
         *
         * @param {object} e The event object
         *
         * @returns {void}
         */
        click : function(e) {
            var t = this;
            t.trigger('pagechange', $(e.target).data('page'), function(page) {
                t.setActivePage(page);
            });
        },

        /**
         * Sets the currently active page on the view
         *
         * @param {Number} page The active page number
         */
        setActivePage : function(page) {
            this.$el.find('a').removeClass('active');
            this.$el.find('a[data-page="' + page + '"]').addClass('active');
        }
    });
});
