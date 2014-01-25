/**
 * Client row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define([
    'core/js/views/DeletableItemView',
    'text!client/views/ClientRowView.html'
], function(DeletableItemView, tpl) {

    return DeletableItemView.extend({
        template: '#client_row-template',
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
                status: d.status,
                totalProjects: d.totalProjects,
                editUrl: this.model.getEditUrl()
            }));
            return this;
        }
    });
});
