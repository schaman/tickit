/**
 * Session model.
 *
 * @type {Backbone.Model}
 */
define(['backbone', 'cookie'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            sessionId: $.cookie('sessionId'),
            userId: $.cookie('uid')
        },

        /**
         * Gets the current session ID
         *
         * @return {string}
         */
        getSessionId: function() {
            return $.cookie('sessionId');
        },

        /**
         * Gets the current user ID
         *
         * @return {number}
         */
        getUserId: function() {
            return $.cookie('uid');
        },

        /**
         * Returns true if the session is authenticated
         *
         * @return {boolean}
         */
        isAuthenticated: function() {
            return Boolean(this.getSessionId() && this.getUserId());
        }
    });
});
