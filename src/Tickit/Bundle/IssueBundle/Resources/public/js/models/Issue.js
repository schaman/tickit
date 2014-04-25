/**
 * Issue model.
 *
 * @type {Backbone.Model}
 */
define([
    'core/js/models/DeletableModel',
    'user/js/models/User'
], function(DeletableModel, User) {
    return DeletableModel.extend({

        defaults: {
            id: null,
            number: null,
            title: '',
            type: '',
            status: '',
            priority: '',
            dueDate: null,
            createdAt: new Date(),
            updatedAt: null,
            assignedTo: '',
            createdBy: '',
            csrfToken: ''
        },

        parse : function(data) {
            data.assignedTo = new User(data.assignedTo);

            // TODO: all DateTime objects should be returned as equal (either objects or strings)
            data.createdAt = new Date(data.createdAt);

            return data;
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
         * Builds a URL for fetching an issue
         */
        url : function() {
            // fetch by ID
            if (null !== this.get('id')) {
                return Routing.generate('');
            }

            // fetch by issue number
            return Routing.generate('');
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
