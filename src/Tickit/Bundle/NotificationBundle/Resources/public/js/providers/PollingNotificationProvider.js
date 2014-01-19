/**
 * Polling notification provider.
 *
 * Provides notification objects by polling the server at a scheduled interval
 *
 * @type {object}
 */
define([
    'modules/request',
    'notification/js/models/Notification',
    'moment',
    'backbone'
], function(Request, Notification, moment) {
    function PollingNotificationProvider() {

        var poll = function() {
            Request.get({
                url: Routing.generate('api_notification_list', { "since": getLastPollTime() }),
                dataType: 'json',
                success: function(resp) {
                    var models = [];
                    if (resp.length) {
                        $.each(resp, function(i, data) {
                            models.push(new Notification(data));
                        });
                        App.Notification.vent.trigger('notification', models);
                    }
                }
            });
        };

        var lastPollTime = new Date();

        /**
         * Gets the last time that notifications were fetched
         *
         * @return {string}
         */
        function getLastPollTime() {
            var pollTime = lastPollTime;
            lastPollTime = new Date();

            return moment(pollTime).format('YYYY-MM-DD HH:mm:ss');
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
            fetchNotifications : poll
        };
    }

    return PollingNotificationProvider;
});