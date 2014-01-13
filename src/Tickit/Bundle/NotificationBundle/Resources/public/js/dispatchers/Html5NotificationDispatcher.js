/**
 * HTML 5 notification dispatcher.
 *
 * Responsible for dispatching new notifications via the HTML5
 * notification API.
 *
 * @type {object}
 */
define([
    'modules/router'
], function(Router) {

    function Html5NotificationDispatcher(options) {
        options = options || {};

        this.vent = options.vent;

        this.vent.on('notification', this.dispatch);
        this.vent.on('setting-change', function(value) {
            if (value && (typeof Notification.permission == 'undefined')) {
                Notification.requestPermission(function(perm) {
                    if (!("permission" in Notification)) {
                        Notification.permission = perm;
                    }
                });
            }
        });
    }

    Html5NotificationDispatcher.prototype.dispatch = function(notifications) {
        if (!_.isArray(notifications)) {
            notifications = [notifications];
        }

        _.each(notifications, function(n) {
            var msg = new Notification('Tickit Notification', {
                dir: 'auto',
                tag: n.get('id'),
                body: n.get('message')
            });
            msg.onclick = function() {
                var uri = n.get('actionUri');
                if (uri.length) {
                    Router.goTo(uri);
                }
            }
        });
    };

    return Html5NotificationDispatcher;
});
