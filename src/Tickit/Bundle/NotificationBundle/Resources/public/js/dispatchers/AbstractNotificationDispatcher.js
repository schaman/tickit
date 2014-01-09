/**
 * Notification dispatcher factory.
 *
 * Responsible for creating a dispatcher factory that best
 * suits the browser's capabilities.
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