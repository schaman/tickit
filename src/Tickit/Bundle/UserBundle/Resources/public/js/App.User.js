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
                }
                return module.currentUser;
            }
        };

        /**
         * Loads the user listing view
         *
         * @return {void}
         */
        module.loadUserList = function() {
            require([
                'user/js/collections/UserCollection',
                'user/js/views/UserListView',
                'user/js/views/UserRowView'
            ], function(collection, listView) {
                var users = new collection;
                users.fetch();
                var view = new listView({
                    collection: users
                });

                App.mainRegion.show(view);
            });
        };

        /**
         * Loads the user create view
         *
         * @return {void}
         */
        module.loadUserCreate = function() {
            require(['user/js/views/UserFormView'], function(view) {
                App.mainRegion.show(new view);
            });
        };

        /**
         * Loads the user edit view
         *
         * @return {void}
         */
        module.loadUserEdit = function(id) {
            require(['user/js/views/UserFormView'], function(view) {
                App.mainRegion.show(new view({ id: id }));
            });
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
