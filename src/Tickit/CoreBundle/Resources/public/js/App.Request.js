/**
 * Request module.
 *
 * Provides methods for making requests to the server.
 *
 * @type {Marionette.Module}
 */
define(['jquery'], function($) {
    return App.module('Request', function(module) {

        /**
         * Performs an XMLHttpRequest of POST type
         *
         * @param {object} params A parameters object, same properties as jQuery.ajax
         *
         * @return {void}
         */
        module.post = function(params) {
            params.type = 'post';
            module.ajax(params);
        };

        /**
         * Performs an XMLHttpRequest of GET type
         *
         * @param {object} params A parameters object, same properties as jQuery.ajax
         *
         * @return {void}
         */
        module.get = function(params) {
            params.type = 'get';
            module.ajax(params);
        };

        /**
         * Performs an XMLHttpRequest of undefined type
         *
         * @param {object} params A parameters object, same properties as jQuery.ajax
         *
         * @return {void}
         */
        module.ajax = function(params) {
            $.ajax({
                url: params.url,
                type: params.type,
                data: params.data,
                dataType: params.dataType,
                success: function(resp, status, xhr) {
                    if (xhr.status === 403) {
                        App.Router.goTo('login');
                    }

                    if (typeof params.success == 'function') {
                        params.success(resp, status, xhr);
                    }
                },
                failure: function() {
                    // TODO: show some error
                }
            });
        };

        /**
         * Bind ajax error handler to the document
         */
        $(document).ajaxError(function (event, jqXHR) {
            if (403 === jqXHR.status) {
                App.Router.goTo('login');
            }
        });
    });
});
