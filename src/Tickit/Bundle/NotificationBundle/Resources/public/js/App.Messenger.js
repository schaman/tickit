/**
 * Messenger module.
 *
 * Responsible for providing methods for messaging the client.
 */
define([
    'jquery',
    'underscore',
    'noty/packaged/jquery.noty.packaged',
    'noty/layouts/topRight',
    'noty/themes/default'
], function($, _, noty) {
    return App.module('Messenger', function(module) {

        $.noty.defaults.timeout = 5000;

        /**
         * Sends a message to the client
         *
         * @param {string} text    The message text
         * @param {string} type    The type of the message
         * @param {object} onClose A callback for the message after close
         */
        module.message = function(text, type, onClose) {
            new noty({
                text: text,
                type: type,
                layout: 'topRight',
                callback: {
                    onClose : typeof onClose == 'function' ? onClose() : function() {}
                }
            });
        };
    });
});
