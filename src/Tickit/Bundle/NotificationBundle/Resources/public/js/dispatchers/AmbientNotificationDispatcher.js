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
    'noty'
], function(Router, noty) {

    function AmbientNotificationDispatcher() {
        App.vent.on('notification', this.dispatch);
    }

    AmbientNotificationDispatcher.prototype.dispatch = function(notifications) {
        if (!_.isArray(notifications)) {
            notifications = [notifications];
        }

        _.each(notifications, function(n) {
            new noty({
                text: n.get('message'),
                callback : {
                    onClose : function() {
                        var uri = n.get('actionUri');
                        if (uri.length) {
                            Router.goTo(uri);
                        }
                    }
                }
            });
        });
    };

    return AmbientNotificationDispatcher;
});
