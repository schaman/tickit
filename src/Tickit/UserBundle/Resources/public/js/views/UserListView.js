/**
 * User list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'user/js/views/UserRowView',
    'text!user/views/UserListView.html'
], function(Template, rowView, tpl) {

    Template.load(tpl);

    return Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'user-list',
        template: '#user_list-template',
        itemView: rowView,

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

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
