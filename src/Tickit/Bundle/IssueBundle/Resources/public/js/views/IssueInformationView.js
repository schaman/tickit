/**
 * Basic issue information view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'text!issue/views/IssueInformationView.html',
    'underscore'
], function(Marionette, tpl, _) {
    return Marionette.ItemView.extend({
        template: '#issue-information-template',

        /**
         * Renders the view
         */
        render: function() {
            var m = this.model;
            this.$el.html(_.template($(tpl).html(), {
                id: m.get('id'),
                number: m.get('number'),
                title: m.get('title'),
                description: m.get('description'),
                estimatedHours: m.get('estimatedHours'),
                actualHours: m.get('actualHours'),
                type: m.getType(),
                status: m.getStatus(),
                priority: m.get('priority'),
                assignedTo: m.getAssignedTo(),
                createdBy: m.getCreatedBy(),
                dueDate: m.getDueDate(),
                createdAt: m.getCreatedAt(),
                updatedAt: m.getUpdatedAt()
            }));

            return this;
        },

        modelEvents: {
            "sync" : "render"
        }
    });
});
