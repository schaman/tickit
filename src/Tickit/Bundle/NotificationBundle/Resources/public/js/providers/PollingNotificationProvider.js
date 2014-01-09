/**
 * Polling notification provider.
 *
 * Provides notification objects by polling the server at a scheduled interval
 *
 * @type {object}
 */
define(['modules/request', 'notification/js/models/Notification', 'Backbone'], function(Request, Notification, Backbone) {
    function PollingNotificationProvider(options) {
        options = options || {};

        var poll = function() {
            Request.get({
                url: Routing.generate('api_notification_list'),
                dataType: 'json',
                success: function(resp) {
                    var models = [];
                    if (resp.length) {
                        $.each(resp, function(i, data) {
                            models.push(new Notification(data));
                        });
                        options.collection.add(models);
                        this.trigger('notification', models);
                    }
                }
            });
        };

        // start the polling at 5 minute intervals
        setInterval(poll, 60000 * 5);

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

    _.extend(PollingNotificationProvider, Backbone.Events);

    return PollingNotificationProvider;
});