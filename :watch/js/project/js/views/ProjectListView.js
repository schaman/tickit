/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'project/js/views/ProjectRowView',
    'core/js/views/ListViewMixin',
    'text!project/views/ProjectListView.html'
], function(Template, FilterableListView, RowView, ListViewMixin, Tpl) {

    Template.load(Tpl);

    var view = FilterableListView.extend({
        tagName: 'div',
        template: '#project_list-template',
        itemView: RowView,

        /**
         * Appends HTML to the view element
         *
         * @param {Backbone.View} collectionView The collection view
         * @param {Backbone.View} itemView       The individual item view
         */
        appendHtml: function(collectionView, itemView) {
            collectionView.$('#project-list').find('tbody').append(itemView.el);
        }
    });

    _.extend(view.prototype, ListViewMixin);

    return view;
});
