/**
 * Pagination view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'text!paging/views/PaginationView.html'], function(Backbone, tpl) {
    return Backbone.View.extend({

        /**
         *
         */
        render : function() {
            // todo: this needs to render with the correct pages (potentially just inject values as params)
            this.$el.html(_.template($(tpl).html()));

            return this;
        }
    });
});
