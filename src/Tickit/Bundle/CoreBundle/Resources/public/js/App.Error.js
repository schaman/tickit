/**
 * Error module.
 *
 * Responsible for displaying error and 404 templates.
 *
 * @type {Marionette.Module}
 */
define(['core/js/views/PageNotFoundView'], function(PageNotFoundView) {
    return App.module('Error', function(module) {

        /**
         * Loads a 404 template into the main region
         */
        module.loadPageNotFound = function() {
            App.mainRegion.show(new PageNotFoundView);
        }
    });
});
