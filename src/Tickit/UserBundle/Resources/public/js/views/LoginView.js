/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['text!/templates/users/login-form', 'modules/request', 'cookie'], function(tpl, Request, cookie) {
    return Backbone.View.extend({

        tagName: 'div',
        className: 'login-wrap',

        /**
         * Event bindings
         */
        events : {
            'click #login-submit' : "submit"
        },

        /**
         * Submits the login form and attempts to log the user in
         *
         * @param {object} e The event object that triggered the submit
         *
         * @return {void}
         */
        submit : function(e) {
            e.preventDefault();
            var $form = $(e.target).closest('form');

            Request.post({
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function(data) {
                    if (data.success) {
                        cookie.set('sessionId', data.sessionId);
                        cookie.set('uid', data.userId);

                        App.Session.load();
                        App.Router.goTo(data.url);
                    }
                }
            });
        },

        /**
         * Renders the view in the document
         *
         * @return {Marionette.Module}
         */
        render: function() {
            this.$el.html(tpl);
            this.$el.find('#login-remember').wrap('<div class="switch" />').parent().bootstrapSwitch();
            return this;
        }
    });
});
