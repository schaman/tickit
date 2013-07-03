/**
 * Session model.
 *
 * @type {Backbone.Model}
 */
define(['backbone', 'cookie'], function(Backbone, cookie) {
    return Backbone.Model.extend({
        defaults: {
            sessionId: null,
            userId: null
        },

        /**
         * Initialises the model
         *
         * @return {void}
         */
        initialize: function() {
            this.load();
        },

        /**
         * Loads latest session data into the model instance
         *
         * @return {void}
         */
        load: function() {
            this.set('sessionId', cookie.get('sessionId'));
            this.set('userId', cookie.get('uid'));
        },

        /**
         * Returns true if the session is authenticated
         *
         * @return {boolean}
         */
        isAuthenticated: function() {
            return Boolean(this.get('sessionId') && this.get('userId'));
        }
    });
});
