/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['tickituser/js/views/LoginView'], function() {

    // load any require templates here

    return App.module('Login', function(module) {

        module.addInitializer(function() {
            // bind any events ??
        });

        /**
         * Loads the main login view
         *
         * @return {void}
         */
        module.loadLoginView = function() {
            if (!App.Session.isAuthenticated()) {
                // display login
            } else {
                App.Router.goTo('dashboard');
            }
        }
    });
});
