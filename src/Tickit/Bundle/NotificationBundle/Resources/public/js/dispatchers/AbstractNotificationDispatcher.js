/**
 * Abstract notification dispatcher.
 *
 * Notification dispatchers are responsible for sending notifications
 * to browsers, operating systems etc.
 *
 * @type {object}
 */
define(function() {
    function AbstractNotificationDispatcher(options) {
        options = options || {};

        this.provider = options.provider;
    }

    return AbstractNotificationDispatcher;
});