/**
 * Project row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!project/views/ProjectRowView.html'], function(Template, tpl) {

    return Backbone.Marionette.ItemView.extend({
        template: '#project_row-template',
        tagName: 'tr',

        /**
         * Renders the template
         */
        render: function() {
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                id: d.id,
                name: d.name,
                created: this.model.getCreated(),
                editUrl: this.model.getEditUrl()
            }));
            return this;
        }
    });
});
