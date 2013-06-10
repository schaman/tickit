/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define(['tickitproject/js/ProjectRowView', 'tpl!tickitproject/views/ProjectListView.html'], function(view, tpl) {
    window.ProjectListView = Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'project-list',
        template: tpl(),
        itemView: view,

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
