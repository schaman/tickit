/**
 * Issue row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define([
    'core/js/views/DeletableItemView',
    'text!issue/views/IssueRowView.html',
], function(DeletableItemView, tpl) {

    return DeletableItemView.extend({
        template: '#issue-row-template',
        tagName: 'tr',

        "events" : {
            "click a.delete-record" : "deleteItem"
        },

        /**
         * Renders the template
         */
        render: function() {
            var m = this.model;
            this.$el.html(_.template($(tpl).html(), {
                id: m.get('id'),
                number: m.get('number'),
                title: m.get('title'),
                type: m.getType(),
                status: m.getStatus(),
                priority: m.get('priority'),
                editUrl: m.getEditUrl()
            }));
            return this;
        }
    });
});
