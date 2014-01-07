/**
 * Polling notification provider.
 *
 * Provides notification objects by polling the server at a scheduled interval
 *
 * @type {object}
 */
define(function() {
    function PollingNotificationProvider(options) {
        options = options || {};

        return {

            /**
             * The notification collection.
             *
             * This will hold all existing notification objects and
             * will be updated when new notifications are found.
             *
             * @type {Backbone.Collection}
             */
            collection : options.collection,

            /**
             * The notification provider
             *
             * @type {object}
             */
            provider : options.provider
        };
    }

    return PollingNotificationProvider;
});