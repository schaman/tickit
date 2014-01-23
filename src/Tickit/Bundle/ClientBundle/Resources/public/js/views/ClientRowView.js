/**
 * Client row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!client/views/ClientRowView.html', 'backbone'], function(Template, tpl, Backbone) {

    return Backbone.Marionette.ItemView.extend({
        template: '#client_row-template',
        tagName: 'tr',

        /**
         * Renders the template
         */
        render: function() {
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                id: d.id,
                name: d.name,
                status: d.status,
                totalProjects: d.totalProjects,
                editUrl: this.model.getEditUrl(),
                deleteUrl: this.model.getDeleteUrl()
            }));
            return this;
        }
    });
});
