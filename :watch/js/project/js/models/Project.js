/**
 * Project model.
 *
 * @type {Backbone.Model}
 */
define(['core/js/models/DeletableModel'], function(DeletableModel) {
    return DeletableModel.extend({

        defaults: {
            id: null,
            name: '',
            csrfToken: '',
            createdAt: new Date()
        },

        /**
         * Casts this model to a string
         *
         * @return {string}
         */
        toString : function() {
            return this.get('name');
        },

        /**
         * Gets the edit URL for this project
         *
         * @returns {string}
         */
        getEditUrl : function() {
            return Routing.generate('project_edit_view', { "id": this.get('id') });
        },

        /**
         * Gets the delete URL for this project
         */
        getDeleteUrl : function() {
            return Routing.generate('project_delete', { "id": this.get('id'), "token": this.get('csrfToken') });
        },

        /**
         * Gets the created datetime in a locale friendly string
         *
         * @returns {string}
         */
        getCreatedAt : function() {
            var date = new Date(this.get('createdAt'));
            return date.toLocaleString();
        }
    });
});
