/**
 * Issue model.
 *
 * @type {Backbone.Model}
 */
define(['core/js/models/DeletableModel'], function(DeletableModel) {
    return DeletableModel.extend({

        defaults: {
            id: null,
            number: '',
            title: '',
            type: '',
            status: '',
            priority: '',
            csrfToken: ''
        },

        /**
         * Casts this model to a string
         *
         * @return {string}
         */
        toString : function() {
            return this.get('title');
        },

        /**
         * Gets the edit URL for this project
         *
         * @returns {string}
         */
        getEditUrl : function() {
            return Routing.generate('issue_edit_view', { "id": this.get('id') });
        },

        /**
         * Gets the delete URL for this issue
         */
        getDeleteUrl : function() {
            return Routing.generate('issue_delete', { "id": this.get('id'), "token": this.get('csrfToken') });
        }
    });
});
