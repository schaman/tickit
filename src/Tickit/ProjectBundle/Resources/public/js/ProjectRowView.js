define(['text!tickitproject/views/ProjectRowView.html'], function(text) {
    // TODO: use base app object to write the "text" variable as a DOM element
    window.ProjectRowView = Backbone.Marionette.ItemView.extend({
        template: tpl(),
        tagName: 'tr'
    });
});
