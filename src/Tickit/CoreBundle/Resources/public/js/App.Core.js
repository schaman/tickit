define(function() {
    /**
     * Application core module.
     *
     * @type {Marionette.Module}
     */
    App.module('Core', function(module) {

        /**
         * Loads view template content into the DOM
         *
         * @param {string} tpl The template content to load into the DOM
         *
         * @return {void}
         */
        module.loadView = function(tpl) {
            $('body').append($(tpl));
        }
    });

    return App.Core;
});
