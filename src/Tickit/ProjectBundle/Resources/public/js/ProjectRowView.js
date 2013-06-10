var ProjectRowView;

define(['text!tickitproject/views/ProjectRowView.html'], function() {
    ProjectRowView = Backbone.Marionette.ItemView.extend({
        template: '#project_row-template',
        tagName: 'tr'
    });
});
