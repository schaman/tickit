/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
var ProjectListView;
define(['tickitproject/js/ProjectRowView', 'text!tickitproject/views/ProjectListView.html'], function() {
    ProjectListView = Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'project-list',
        template: '#project_list-template',
        itemView: ProjectRowView,

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
