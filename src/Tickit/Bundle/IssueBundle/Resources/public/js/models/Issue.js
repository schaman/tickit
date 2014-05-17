/**
 * Issue model.
 *
 * @type {Backbone.Model}
 */
define([
    'core/js/models/DeletableModel',
    'user/js/models/User',
    'issue/js/models/IssueType',
    'issue/js/models/IssueStatus'
], function(DeletableModel, User, IssueType, IssueStatus) {
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
            csrfToken: '',
            estimatedHours: 0,
            actualHours: 0
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
        },

        /**
         * Gets the issue type
         *
         * @return {Backbone.Model}
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
        },

        /**
         * Gets the user assigned to this issue
         *
         * @return {Backbone.Model}
         */
        getAssignedTo : function() {
            return new User(this.get('assignedTo'));
        },

        /**
         * Gets the created datetime
         *
         * @return {Date}
         */
        getCreatedAt : function() {
            return new Date(this.get('createdAt'));
        },

        /**
         * Gets the created by
         *
         * @return {Backbone.Model}
         */
        getCreatedBy : function() {
            return new User(this.get('createdBy'))
        },

        /**
         * Gets the due date for this issue
         *
         * @return {Date}
         */
        getDueDate : function() {
            return new Date(this.get('dueDate'))
        },

        /**
         * Gets the updated date for this issue
         *
         * @return {Date}
         */
        getUpdatedAt : function() {
            return new Date(this.get('updatedAt'));
        }
    });
});
