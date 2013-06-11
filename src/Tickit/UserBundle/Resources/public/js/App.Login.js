/**
 * Application login module.
 *
 * Provides methods for helping with login
 *
 * @type {Marionette.Module}
 */
define(['modules/core'], function(Core) {

    // load any require templates here

    return App.module('Core', function(module) {

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
