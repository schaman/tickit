/**
 * Application templating module.
 *
 * Provides methods for helping with templating in the application.
 *
 * @type {Marionette.Module}
 */
define(function() {
    App.module('Template', function(module) {

        /**
         * Loads view template content into the DOM
         *
         * @param {string} tpl The template content to load into the DOM
         *
         * @return {void}
         */
        module.load = function(tpl) {
            $('body').append($(tpl));
        }
    });

    return App.Template;
});
