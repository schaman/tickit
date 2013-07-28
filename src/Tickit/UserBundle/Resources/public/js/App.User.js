/**
 * Application user module.
 *
 * Responsible for providing user related functionality
 *
 * @type {Marionette.Module}
 */
define([
    'user/js/models/User',
    'user/js/models/Session'
], function(User) {
    return App.module('User', function(module) {

        module.currentUser = null;
        module.startWithParent = false;

        /**
         * Loads the currently logged in user into context
         *
         * @return {Backbone.Model}
         */
        module.loadCurrentUser = function(callback) {
            if (null === module.currentUser) {
                module.currentUser = new User();
                return module.currentUser.fetch({
                    id: App.Session.get('userId'),
                    success: function(user) {
                        if (typeof callback == 'function') {
                            callback(user);
                        }
                    }
                });
            } else {
                if (typeof callback == 'function') {
                    callback(module.currentUser);
                } else {
                    return module.currentUser;
                }
            }
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
