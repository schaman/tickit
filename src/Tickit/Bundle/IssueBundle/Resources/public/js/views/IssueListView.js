/**
 * Issue list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'issue/js/views/IssueRowView',
    'core/js/views/ListViewMixin',
    'text!issue/views/IssueListView.html'
], function(Template, FilterableListView, RowView, ListViewMixin, Tpl) {

    Template.load(Tpl);

    var view = FilterableListView.extend({
        tagName: 'div',
        template: '#issue-list-template',
        itemView: RowView,

        /**
         * Appends HTML to the view element
         *
         * @param {Backbone.View} collectionView The collection view
         * @param {Backbone.View} itemView       The individual item view
         */
        appendHtml: function(collectionView, itemView) {
            collectionView.$('#issue-list').find('tbody').append(itemView.el);
        }
    });

    _.extend(view.prototype, ListViewMixin);

    return view;
});
