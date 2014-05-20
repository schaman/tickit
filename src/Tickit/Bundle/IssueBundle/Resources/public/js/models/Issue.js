/**
 * Issue model.
 *
 * @type {Backbone.Model}
 */
define([
    'core/js/models/DeletableModel',
    'issue/js/models/IssueType',
    'issue/js/models/IssueStatus'
], function(DeletableModel, IssueType, IssueStatus) {
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
         * Gets the edit URL for this issue
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
        },

        /**
         * Gets the view URL for this issue
         *
         * @return {string}
         */
        getViewUrl : function() {
            return Routing.generate('issue_view', { number: this.get('number') });
        },

        /**
         * Gets the issue type
         *
         * @returns {Backbone.Model}
         */
        getType : function() {
            return new IssueType(this.get('type'));
        },

        /**
         * Gets the issue status
         *
         * @return {Backbone.Model}
         */
        getStatus : function () {
            return new IssueStatus(this.get('status'));
        }
    });
});
