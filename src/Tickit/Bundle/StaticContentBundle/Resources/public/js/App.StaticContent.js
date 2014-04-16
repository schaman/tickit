/**
 * Application static content module.
 *
 * Provides methods for loading static content.
 *
 * @type {Marionette.Module}
 */
define(['staticcontent/js/views/NotFoundView'], function(NotFoundView) {
    return App.module('Login', function(module) {

        /**
         * Loads the page not found view
         *
         * @return {void}
         */
        module.loadPageNotFound = function() {
            App.mainRegion.show(new NotFoundView());
        };
    });
});
