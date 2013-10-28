/**
 * Application templating module.
 *
 * Provides methods for helping with templating in the application.
 *
 * @type {Marionette.Module}
 */
define(['modules/request'], function(Request) {
    return App.module('Template', function(module) {

        /**
         * Template cache.
         *
         * Entries in here are stored as key value pairs, where the key is the
         * cleaned URL used to fetch the template and the value is template content
         * (whitespace removed to reduce storage size).
         *
         * @type {object}
         */
        var cache = {};

        /**
         * Loads view template content into the DOM
         *
         * @param {string} tpl The template content to load into the DOM
         *
         * @return {void}
         */
        module.load = function(tpl) {
            $('body').append($(tpl));
        };

        /**
         * Fetches template contents based off a URL.
         *
         * If a value exists in the template cache for the URL identifier, then the form will be
         * returned from the cache (unless "forceFetch" is set to true, in which case it will be
         * fetched from the server).
         *
         * @param {string}   url        The URL of the template to fetch.
         * @param {function} callback   The callback function to be executed when the template is fetched
         * @param {boolean}  forceFetch Boolean indicating whether or not to bypass the cache (defaults to false)
         *
         * @return {void}
         */
        module.fetch = function(url, callback, forceFetch) {
            var id = encodeURIComponent(url);
            forceFetch = forceFetch || false;

            if (typeof cache[id] != 'undefined' && forceFetch === false) {
                callback(cache[id]);
            } else {
                Request.get({
                    url: url,
                    dataType: 'html',
                    success: function(tpl) {
                        cache[id] = $(tpl);
                        callback(cache[id]);
                    }
                });
            }
        }
    });
});
