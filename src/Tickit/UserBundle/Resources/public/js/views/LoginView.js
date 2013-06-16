/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['text!/users/login-form'], function(tpl) {
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
            console.log('submit');
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
