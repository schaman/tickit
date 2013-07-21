/**
 * Navigation module.
 *
 * @type {Marionette.Module}
 */
define([
    'tickitnavigation/js/collections/NavigationItemCollection',
    'tickitnavigation/js/views/NavigationView',
    'tickitnavigation/js/views/ProfileNavigationView',
    'modules/user'
], function(NavigationItemCollection, NavigationView, ProfileNavigationView, User) {

    return App.module('Navigation', function(module) {
        module.startWithParent = true;

        /**
         * Loads the main application nav
         *
         * @return {void}
         */
        module.loadMainNavigation = function() {
            var navItems = new NavigationItemCollection;
            navItems.fetch();
            var view = new NavigationView({
                collection: navItems
            });

            App.navRegion.show(view);
        };

        /**
         * Loads the header navigation
         *
         * @return {void}
         */
        module.loadHeaderNavigation = function() {
            User.loadCurrentUser(function(user) {
                var view = new ProfileNavigationView({ model: user });
                App.toolbarRegion.show(view);
            });
        }
    });
});
