/**
 * Project row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!project/views/ProjectRowView.html'], function(Template, tpl) {

    Template.load(tpl);

    return Backbone.Marionette.ItemView.extend({
        template: '#project_row-template',
        tagName: 'tr'
    });
});
