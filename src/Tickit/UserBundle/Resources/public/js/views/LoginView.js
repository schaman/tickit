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
            var me = this;

            Request.post({
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function(data) {
                    if (data.success) {
                        cookie.set('sessionId', data.sessionId);
                        cookie.set('uid', data.userId);

                        App.Session.load();
                        App.Router.goTo(data.url);
                    } else {
                        me.addError(data.error);
                    }
                }
            });
        },

        /**
         * Displays an error on the login form
         *
         * @param {string} error The error message to display
         */
        addError : function(error) {
            this.$el.find('div.alert-error').remove();
            // TODO: make this an error template in the CoreBundle
            this.$el.find('div.twitter-login').after('<div class="alert alert-error"><p>' + error + '</p></div>');
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
