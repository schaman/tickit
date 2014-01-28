/**
 * Page not found view (404)
 *
 * @type {Backbone.View}
 */
define(['backbone', 'text!core/views/PageNotFound.html'], function(Backbone, tpl) {
    return Backbone.View.extend({

        /**
         * Renders the view
         */
        render : function() {
            this.$el.html($(tpl).html());

            return this;
        }
    });
});
