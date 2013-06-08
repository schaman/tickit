var ProjectListView = Backbone.Marionette.CompositeView.extend({
    tagName: 'table',
    id: 'project-list',
    template: '#project_list-template',
    itemView: ProjectRowView,

    appendHtml: function(collectionView, itemView) {
        collectionView.$('tbody').append(itemView.el);
    }
});