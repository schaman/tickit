/**
 * Navigation module.
 *
 * @type {Marionette.Module}
 */
define([
    'navigation/js/collections/NavigationItemCollection',
    'navigation/js/views/NavigationView',
    'navigation/js/views/ProfileNavigationView',
    'modules/user'
], function(NavigationItemCollection, NavigationView, ProfileNavigationView, User) {

    return App.module('Navigation', function(module) {
        module.startWithParent = true;

        /**
         * Loads all navigation elements
         *
         * @return {void}
         */
        module.loadNavigation = function() {
            this.loadHeaderNavigation();
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
