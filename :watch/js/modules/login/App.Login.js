/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['user/js/views/LoginView'], function(LoginView) {
    return App.module('Login', function(module) {

        /**
         * Loads the main login view
         *
         * @return {void}
         */
        module.loadLoginView = function() {
            if (!App.Session.isAuthenticated()) {
                App.loginRegion.show(new LoginView());
            } else {
                App.Router.goTo('dashboard');
            }
        };
    });
});
