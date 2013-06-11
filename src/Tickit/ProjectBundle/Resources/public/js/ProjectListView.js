/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define(['modules/template', 'tickitproject/js/ProjectRowView', 'text!tickitproject/views/ProjectListView.html'], function(Template, rowView, tpl) {

    Template.loadView(tpl);

    return Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'project-list',
        template: '#project_list-template',
        itemView: rowView,

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
