/**
 * User model.
 *
 * Represents a user in the application.
 *
 * @type {Backbone.Model}
 */
define([
    'backbone'
], function(Backbone) {
    return Backbone.Model.extend({

        defaults: {
            id: null,
            username: '',
            email: '',
            forename: '',
            surname: '',
            avatarUrl: ''
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
         * Defines the root URL where the model data is fetched
         *
         * @return {string}
         */
        urlRoot: function() {
            return Routing.generate('api_user_fetch');
        }
    });
});
