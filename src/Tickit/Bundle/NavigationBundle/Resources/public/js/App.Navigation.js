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
    'navigation/js/views/SettingsNavigationView',
    'modules/user'
], function(
    NavigationItemCollection,
    NavigationView,
    ProfileNavigationView,
    ToolbarLayout,
    SettingsNavigationView,
    User
) {

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
                var layout = new ToolbarLayout();
                layout.on('show', function() {
                    var navItems = new NavigationItemCollection();
                    var settingsNavItems = new NavigationItemCollection([], { name: 'settings' });

                    layout.profileRegion.show(new ProfileNavigationView({ model: user }));
                    layout.navRegion.show(new NavigationView({ collection: navItems }));
                    layout.settingsNavRegion.show(new SettingsNavigationView({ collection: settingsNavItems }));
                });
                App.toolbarRegion.show(layout);
            });
        };
    });
});
