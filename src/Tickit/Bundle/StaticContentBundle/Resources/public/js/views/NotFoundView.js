/**
 * Page not found view.
 *
 * @type {Backbone.View}
 */
define([
    'backbone',
    'text!staticcontent/views/NotFoundView.html'
], function(Backbone, tpl) {
    return Backbone.View.extend({

        template: '#page-not-found-template',

        /**
         * Renders the template
         */
        render: function() {
            this.$el.html(tpl);
        }
    });
});
