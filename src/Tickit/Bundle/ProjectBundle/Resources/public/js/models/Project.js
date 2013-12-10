/**
 * Project model.
 *
 * @type {Backbone.Model}
 */
define(function() {
    return Backbone.Model.extend({

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
            return Routing.generate('project_delete', { "id": this.get('id'), "token": this.get('csrf_token') });
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
