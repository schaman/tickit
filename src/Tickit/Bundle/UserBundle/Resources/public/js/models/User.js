/**
 * User model.
 *
 * Represents a user in the application.
 *
 * @type {Backbone.Model}
 */
define(['core/js/models/DeletableModel'], function(DeletableModel) {
    return DeletableModel.extend({

        defaults: {
            id: null,
            username: '',
            email: '',
            forename: '',
            surname: '',
            avatarUrl: '',
            csrfToken: '',
            lastActive: new Date()
        },

        /**
         * Casts the model to a string
         *
         * @return {string}
         */
        toString : function() {
            return this.getFullName();
        },

        /**
         * Gets the full name of the user
         *
         * @returns {string}
         */
        getFullName: function() {
            return this.get('forename') + ' ' + this.get('surname');
        },

        /**
         * Gets the date and time that this user was last active
         *
         * @return {Date}
         */
        getLastActive: function() {
            return new Date(this.get('lastActivity'));
        },

        /**
         * Gets the edit URL for this user
         *
         * @returns {string}
         */
        getEditUrl : function() {
            return Routing.generate('user_edit_view', { "id": this.get('id') });
        },

        /**
         * Gets the delete URL for this user
         *
         * @return {string}
         */
        getDeleteUrl: function() {
            return Routing.generate('user_delete', { "id": this.get('id'), 'token': this.get('csrfToken') });
        },

        /**
         * Defines the root URL where the model data is fetched
         *
         * @return {string}
         */
        urlRoot: function() {
            return Routing.generate('api_user_fetch');
        },

        /**
         *
         * @returns {boolean}
         */
        isAdmin : function() {
            return true === this.get('admin');
        }
    });
});
