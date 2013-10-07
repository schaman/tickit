/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Backbone.View}
 */
define([
    'modules/request',
    'core/js/views/SingleFormErrorView',
    'core/js/models/FormError',
    'modules/template'
], function(Request, FormErrorView, FormError, Template) {
    return Backbone.View.extend({

        tagName: 'div',
        className: 'login-wrap',
        errorRegion: null,

        /**
         * Event bindings
         */
        events : {
            'click #login-submit' : "submit"
        },

        initialize: function() {
            this.errorRegion = new Backbone.Marionette.Region({
                el: '#errors'
            });
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

            this.clearErrors();

            Request.post({
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function(data) {
                    if (data.success) {
                        $.cookie('uid', data.userId);
                        App.loginRegion.close(function() {
                            App.Router.goTo(data.url);
                        });
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
         *
         * @return {void}
         */
        addError: function(error) {
            var errorView = new FormErrorView({
                model: new FormError({error: error})
            });
            this.errorRegion.show(errorView);
        },

        /**
         * Clears errors on the login form
         *
         * @return {void}
         */
        clearErrors: function() {
            this.errorRegion.reset();
        },

        /**
         * Renders the view in the document
         *
         * @return {Marionette.Module}
         */
        render: function() {
            var t = this;
            Template.fetch('/templates/users/login-form', function(tpl) {
                t.$el.html(tpl);
            });

            return this;
        },

        /**
         * Animates the login view in
         *
         * @param {function} cb A callback function
         */
        animateIn: function(cb) {
            this.$el.fadeIn();
            if (cb) {
                cb();
            }
        },

        /**
         * Animates the login view out
         *
         * @param {function} cb A callback function
         */
        animateOut: function(cb) {
            this.$el.fadeOut(cb);
        }
    });
});
