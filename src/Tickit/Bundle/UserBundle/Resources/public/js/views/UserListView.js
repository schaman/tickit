/**
 * User list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'user/js/views/UserRowView',
    'text!user/views/UserListView.html'
], function(Template, FilterableListView, RowView, Tpl) {

    Template.load(Tpl);

    return FilterableListView.extend({
        tagName: 'div',
        template: '#user_list-template',
        itemView: RowView,

        events: {
            "click a": "linkClick"
        },

        /**
         * Handles a click event on an <a> tag
         */
        linkClick : function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
        },

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
});
