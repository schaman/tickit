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

            new UserAvatarView({
                el: this.$el.find('.assigned-to'),
                model: this.model.getAssignedTo()
            }).render();

            new UserAvatarView({
                el: this.$el.find('.created-by'),
                model: this.model.getCreatedBy()
            }).render();

            return this;
        },

        modelEvents: {
            "sync" : "render"
        }
    });
});
