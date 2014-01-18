/**
 * Polling notification provider.
 *
 * Provides notification objects by polling the server at a scheduled interval
 *
 * @type {object}
 */
define(['modules/request', 'notification/js/models/Notification', 'moment', 'backbone'], function(Request, Notification, moment) {
    function PollingNotificationProvider(options) {
        options = options || {};

        var lastPollTime = null;

        var poll = function() {
            Request.get({
                url: Routing.generate('api_notification_list', { "since": getLastPollTime() }),
                dataType: 'json',
                success: function(resp) {
                    var models = [];
                    // TODO: this needs to store a maximum of 25 notifications
                    if (resp.length) {
                        $.each(resp, function(i, data) {
                            models.push(new Notification(data));
                        });
                        options.collection.add(models);
                        App.vent.trigger('notification', models);
                    }
                }
            });
        };

        /**
         * Gets the last time that notifications were fetched
         */
        function getLastPollTime() {
            if (null === lastPollTime) {
                lastPollTime = new Date();
                return null;
            }

            var lastSince = lastPollTime;
            lastPollTime = new Date();

            return moment(lastSince).format('YYYY-MM-DD HH:mm:ss');
        }

        // start the polling at 1 minute intervals
        setInterval(poll, 60000);

        return {

            /**
             * Triggers a fetch of new notifications
             *
             * This provides a way of forcing a fetch of new notifications
             * in the event that the client cannot wait for the poll event
             *
             * @type {function}
             */
            fetchNotifications : poll,

            /**
             * The notification collection.
             *
             * This will hold all existing notification objects and
             * will be updated when new notifications are found.
             *
             * @type {Backbone.Collection}
             */
            collection : options.collection
        };
    }

    return PollingNotificationProvider;
});