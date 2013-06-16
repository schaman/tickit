/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['tickituser/js/views/LoginView'], function(LoginView) {

    // load any require templates here

    return App.module('Login', function(module) {

        /**
         * Loads the main login view
         *
         * @return {void}
         */
        module.loadLoginView = function() {
            if (!App.Session.isAuthenticated()) {
                App.mainRegion.show(new LoginView());
            } else {
                App.Router.goTo('dashboard');
            }
        }
    });
});
