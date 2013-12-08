/**
 * Client list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'client/js/views/ClientRowView',
    'text!client/views/ClientListView.html',
    'backbone'
], function(Template, FilterableListView, RowView, Tpl, Backbone) {

    Template.load(Tpl);

    return FilterableListView.extend({
        tagName: 'div',
        template: '#client_list-template',
        itemView: RowView,

        events: {
            "click a": "linkClick"
        },

        /**
         * Handles a click event on an <a> tag
         *
         */
        linkClick : function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
        },

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
});
