/**
 * The main application region.
 *
 * Provides a region for displaying all main application content
 *
 * @type {Marionette.Region}
 */
define(['marionette'], function(Marionette) {
    return Marionette.Region.extend({
        el: '#content-container',

        /**
         * Called whenever a view is displayed inside this region
         *
         * @return {void}
         */
        onShow: function() {
            if (App.Session.isAuthenticated() && typeof App.Navigation == 'undefined') {
                require(['modules/navigation'], function(Navigation) {
                    Navigation.loadHeaderNavigation();
                    Navigation.loadMainNavigation();
                });
                require(['modules/notification'], function(Notification) {
                    Notification.loadNotifications();
                });
            }
        }
    });
});
