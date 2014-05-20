/**
 * User list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'user/js/views/UserRowView',
    'core/js/views/ListViewMixin',
    'text!user/views/UserListView.html'
], function(Template, FilterableListView, RowView, ListViewMixin, Tpl) {

    Template.load(Tpl);

    var view = FilterableListView.extend({
        tagName: 'div',
        template: '#user_list-template',
        itemView: RowView,

        /**
         * Appends HTML to the view element
         *
         * @param {Backbone.View} collectionView The collection view
         * @param {Backbone.View} itemView       The individual item view
         */
        appendHtml: function(collectionView, itemView) {
            collectionView.$('#user-list').find('tbody').append(itemView.el);
        }
    });

    _.extend(view.prototype, ListViewMixin);

    return view;
});
