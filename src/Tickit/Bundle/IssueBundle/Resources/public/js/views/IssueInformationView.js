/**
 * Basic issue information view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'text!issue/views/IssueInformationView.html',
    'underscore',
    'user/js/views/UserNameWithAvatarView'
], function(Marionette, tpl, _, UserAvatarView) {
    return Marionette.ItemView.extend({
        template: '#issue-information-template',

        assignedToView: null,
        createdByView: null,

        modelEvents: {
            "sync" : "render"
        },

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
                dueDate: m.getDueDate(),
                createdAt: m.getCreatedAt(),
                updatedAt: m.getUpdatedAt()
            }));

            this.assignedToView = new UserAvatarView({
                el: this.$el.find('.assigned-to'),
                model: this.model.getAssignedTo()
            });

            this.createdByView = new UserAvatarView({
                el: this.$el.find('.created-by'),
                model: this.model.getCreatedBy()
            });

            this.createdByView.render();
            this.assignedToView.render();

            return this;
        },

        /**
         * Closes the view.
         *
         * Cleans up any subviews that are no longer needed.
         */
        onClose : function() {
            this.createdByView.close();
            this.assignedToView.close();
        }
    });
});
