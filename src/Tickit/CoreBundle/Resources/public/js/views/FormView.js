/**
 * Abstract form view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'modules/request'], function(Backbone, Request) {
    return Backbone.View.extend({
        /**
         * Event bindings
         */
        events: {
            "submit" : "onSubmit"
        },

        /**
         * Handles a submit event
         *
         * @return {void}
         */
        onSubmit : function(e) {
            var me = this;
            e.preventDefault();
            var $form = $(e.target).closest('form');

            Request.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                success: function(resp) {
                    if (resp.success) {
                        App.Router.goTo(resp.returnUrl);
                    } else {
                        me.$el.html(resp.form);
                    }
                }
            });

        },

        /**
         * Renders the view
         *
         * @returns {Backbone.View}
         */
        render: function() {
            var me = this;
            Request.get({
                url: this.getUrl(),
                success: function(resp) {
                    me.$el.html(resp);
                }
            });
            return this;
        }
    });
});
