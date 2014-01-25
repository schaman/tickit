/**
 * Client model
 *
 * @type {Backbone.Model}
 */
define(['core/js/models/DeletableModel'], function(DeletableModel) {
    return DeletableModel.extend({

        defaults: {
            id: null,
            name: '',
            url: '',
            status: 'active',
            totalProjects: 0,
            csrfToken: '',
            created: new Date()
        },

        /**
         * Casts this model to a string
         *
         * @return {string }
         */
        toString : function() {
            return this.get('name');
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
            return Routing.generate('client_delete', { id: this.get('id'), token: this.get('csrfToken') });
        }
    });
});
