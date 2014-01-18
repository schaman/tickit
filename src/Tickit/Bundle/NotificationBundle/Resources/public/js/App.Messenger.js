/**
 * Messenger module.
 *
 * Responsible for providing methods for messaging the client.
 */
define([
    'jquery',
    'noty',
    'noty/layout',
    'noty/theme'
], function($) {
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
            noty({
                text: text,
                type: type,
                layout: 'topRight',
                callback: {
                    onClose : typeof onClose == 'function' ? onClose : function() {}
                }
            });
        };
    });
});
