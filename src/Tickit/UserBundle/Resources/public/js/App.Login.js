/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['modules/core'], function() {

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
            console.log('login view load');
        }
    });
});
