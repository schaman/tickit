/**
 * Project row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!tickitproject/views/ProjectRowView.html'], function(Template, tpl) {

    Template.loadView(tpl);

    return Backbone.Marionette.ItemView.extend({
        template: '#project_row-template',
        tagName: 'tr'
    });
});
