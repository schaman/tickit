/**
 * Navigation module.
 *
 * @type {Marionette.Module}
 */
define([
    'tickitcore/js/collections/NavigationItemCollection',
    'tickitcore/js/views/NavigationView'
], function(NavigationItemCollection, NavigationView) {

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
        }
    });
});
