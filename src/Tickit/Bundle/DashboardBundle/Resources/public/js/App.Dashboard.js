/**
 * Application dashboard module.
 *
 * Provides functionality for handling anything dashboard related.
 *
 * @type {Marionette.Module}
 */
define(function() {
    return App.module('Dashboard', function(module) {

        module.startWithParent = false;

        /**
         * Loads the main dashboard view
         *
         * @return {void}
         */
        module.loadDashboard = function() {
            var view = new Backbone.View({
                tagName: 'div',
                render: function() {
                    this.$el.html('<h1>Dashboard view</h1>');
                }
            });

            App.mainRegion.show(view);
        };
    });
});
