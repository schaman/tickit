/**
 * Project row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define([
    'core/js/views/DeletableItemView',
    'text!project/views/ProjectRowView.html',
], function(DeletableItemView, tpl) {

    return DeletableItemView.extend({
        template: '#project_row-template',
        tagName: 'tr',

        "events" : {
            "click a.delete-record" : "deleteItem"
        },

        /**
         * Renders the template
         */
        render: function() {
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                id: d.id,
                name: d.name,
                createdAt: this.model.getCreatedAt(),
                editUrl: this.model.getEditUrl()
            }));
            return this;
        }
    });
});
