/**
 * Messenger module.
 *
 * Responsible for providing methods for messaging the client.
 */
define([
    'jquery',
    'noty',
    'notification/js/noty/themes/tickit'
], function($, n, tickitTheme) {
    return App.module('Messenger', function(module) {

        $.noty.defaults.timeout = 5000;

        /**
         * Sends a message to the client
         *
         * @param {string}   text    The message text
         * @param {string}   type    The type of the message
         * @param {function} onClose A callback for the message after close
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

        /**
         * Asks the user for confirmation of an action
         *
         * @param {string}   text           The text to display in the confirmation
         * @param {function} onConfirm      The callback executed after the user confirms the action
         * @param {string}   confirmBtnText The text of the confirm button (defaults to "OK")
         * @param {string}   cancelBtnText  The text of the cancel button (defaults to "Cancel")
         */
        module.confirm = function(text, onConfirm, confirmBtnText, cancelBtnText) {
            noty({
                theme: tickitTheme,
                text: text,
                type: 'confirm',
                layout: 'center',
                buttons: [{
                    addClass: 'btn btn-primary',
                    text: confirmBtnText || 'OK',
                    onClick : function($noty) {
                        $noty.close();
                        onConfirm($noty, this);
                    }
                }, {
                    addClass: 'btn btn-cancel',
                    text: cancelBtnText || 'Cancel',
                    onClick : function($noty) {
                        $noty.close();
                    }
                }],
                modal: true,
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
        }
    });
});
