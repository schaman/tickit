/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'project/js/views/ProjectRowView',
    'text!project/views/ProjectListView.html'
], function(Template, rowView, tpl) {

    Template.load(tpl);

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
