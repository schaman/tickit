/**
 * Project row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['text!tickitproject/views/ProjectRowView.html'], function(tpl) {

    $('body').append($(tpl));

    return Backbone.Marionette.ItemView.extend({
        template: '#project_row-template',
        tagName: 'tr'
    });
});
