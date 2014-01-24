/**
 * Messenger module.
 *
 * Responsible for providing methods for messaging the client.
 */
define([
    'jquery',
    'noty',
    'notification/js/noty/layouts/topRight',
    'notification/js/noty/themes/tickit'
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
                theme: 'notification',
                text: text,
                type: type,
                layout: 'topRight',
                callback: {
                    onClose : typeof onClose == 'function' ? onClose : function() {}
                },
                animation: {
                    open: {
                        opacity: 'toggle'
                    },
                    close: {
                        opacity: 'toggle'
                    },
                    easing: 'swing',
                    speed: 500
                }
            });
        };
    });
});
