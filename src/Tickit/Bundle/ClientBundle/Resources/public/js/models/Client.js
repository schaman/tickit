/**
 * Client model
 *
 * @type {Backbone.Model}
 */
define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({

        defaults: {
            id: null,
            name: '',
            url: '',
            status: 'active',
            totalProjects: 0,
            created: ''
        },

        /**
         * Gets the edit URL for this client
         *
         * @return {string}
         */
        getEditUrl : function() {
            return Routing.generate('client_edit_view', { id: this.get('id') });
        },

        /**
         * Gets the delete URL for this client
         *
         * @return {string}
         */
        getDeleteUrl : function() {
            return Routing.generate('client_delete', { id: this.get('id'), token: this.get('csrf_token') });
        }
    });
});
