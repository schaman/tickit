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
         * Fetches a template from the cache by its ID
         *
         * @param {string} id The identifier of the template
         *
         * @return {string}
         */
        var fetchFromCache = function(id) {
            return $(cache[id]);
        };

        /**
         * Returns true if there is cached entry for the given ID
         *
         * @param {string} id The template ID to check in the cache
         *
         * @return {boolean}
         */
        var isCached = function(id) {
            return typeof cache[id] != 'undefined';
        };

        /**
         * Stores a template in the cache
         *
         * @param {string} id  The template identifier
         * @param {string} tpl The template string
         */
        var storeInCache = function(id, tpl) {
            cache[id] = tpl.trim();
        };

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

            if (isCached(id) && forceFetch === false) {
                callback(fetchFromCache(id));
            } else {
                Request.get({
                    url: url,
                    dataType: 'html',
                    success: function(tpl) {
                        storeInCache(id, tpl);
                        callback(fetchFromCache(id));
                    }
                });
            }
        };
    });
});
