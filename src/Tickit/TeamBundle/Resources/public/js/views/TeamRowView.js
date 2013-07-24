/**
 * Team row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!team/views/TeamRowView.html'], function(Template, tpl) {

    Template.load(tpl);

    return Backbone.Marionette.ItemView.extend({
        template: '#team_row-template',
        tagName: 'tr'
    });
});
