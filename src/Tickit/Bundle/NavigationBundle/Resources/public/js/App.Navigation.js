/**
 * Navigation module.
 *
 * @type {Marionette.Module}
 */
define([
    'navigation/js/collections/NavigationItemCollection',
    'navigation/js/views/NavigationView',
    'navigation/js/views/ProfileNavigationView',
    'navigation/js/layouts/ToolbarLayout',
    'modules/user'
], function(NavigationItemCollection, NavigationView, ProfileNavigationView, ToolbarLayout, User) {

    return App.module('Navigation', function(module) {
        module.startWithParent = true;

        /**
         * Loads all navigation elements
         *
         * @return {void}
         */
        module.loadNavigation = function() {
            this.loadToolbarNavigation();
        };

        /**
         * Loads the toolbar navigation
         *
         * @return {void}
         */
        module.loadToolbarNavigation = function() {
            User.loadCurrentUser(function(user) {
                var layout = new ToolbarLayout;
                layout.on('render', function() {
                    var navItems = new NavigationItemCollection;
                    layout.profileRegion.show(new ProfileNavigationView({ model: user }));
                    layout.navRegion.show(new NavigationView({ collection: navItems }));
                });
                App.toolbarRegion.show(layout);
            });
        }
    });
});
