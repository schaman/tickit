/**
 * Client list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'client/js/views/ClientRowView',
    'core/js/views/ListViewMixin',
    'text!client/views/ClientListView.html',
], function(Template, FilterableListView, RowView, ListViewMixin, Tpl) {

    Template.load(Tpl);

    var view = FilterableListView.extend({
        tagName: 'div',
        template: '#client_list-template',
        itemView: RowView,

        /**
         * Appends HTML to the view element
         *
         * @param {Backbone.View} collectionView The collection view
         * @param {Backbone.View} itemView       The item view
         */
        appendHtml: function(collectionView, itemView) {
            collectionView.$('#client-list').find('tbody').append(itemView.el);
        }
    });

    _.extend(view.prototype, ListViewMixin);

    return view;
});
