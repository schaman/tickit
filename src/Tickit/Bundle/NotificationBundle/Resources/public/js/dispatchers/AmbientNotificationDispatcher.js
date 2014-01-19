/**
 * Ambient notification dispatcher.
 *
 * Responsible for dispatching new notifications via the in app
 * notification system.
 *
 * @type {object}
 */
define([
    'modules/router',
    'modules/messenger'
], function(Router, Messenger) {

    function AmbientNotificationDispatcher() {
        App.Notification.vent.on('notification', this.dispatch);
    }

    AmbientNotificationDispatcher.prototype.dispatch = function(notifications) {
        if (!_.isArray(notifications)) {
            notifications = [notifications];
        }

        _.each(notifications, function(n) {
            Messenger.message(n.get('message'), 'info', function() {
                var uri = n.get('actionUri');
                if (uri.length) {
                    Router.goTo(uri);
                }
            });
        });
    };

    return AmbientNotificationDispatcher;
});
