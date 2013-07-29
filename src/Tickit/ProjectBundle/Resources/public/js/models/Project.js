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
            // TODO: need to load CSRF
        },

        /**
         * Gets the created datetime in a locale friendly string
         *
         * @returns {string}
         */
        getCreated : function() {
            var date = new Date(this.get('created'));
            return date.toLocaleString();
        }
    });
});
