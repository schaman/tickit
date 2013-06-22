/**
 * Application user module.
 *
 * Responsible for providing user related functionality
 *
 * @type {Marionette.Module}
 */
define([
    'tickituser/js/models/User',
    'tickituser/js/models/Session'
], function(User) {
    return App.module('User', function(module) {

        module.currentUser = null;
        module.startWithParent = false;

        /**
         * Loads the currently logged in user into context
         *
         * @return {Backbone.Model}
         */
        module.loadCurrentUser = function() {
            if (null === module.currentUser) {
                module.currentUser = new User();
                module.currentUser.fetch({ id: App.Session.get('userId') });
            }
            return module.currentUser;
        };

        /**
         * Gets the currently logged in user
         *
         * @return {Backbone.Model}
         */
        module.getCurrentUser = function() {
            return this.currentUser;
        }
    });
});
