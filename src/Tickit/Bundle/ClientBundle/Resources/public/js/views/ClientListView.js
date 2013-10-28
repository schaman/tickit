/**
 * Client list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'client/js/views/ClientRowView',
    'text!client/views/ClientListView.html',
    'backbone'
], function(Template, rowView, tpl, Backbone) {

    Template.load(tpl);

    return Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'client-list',
        template: '#client_list-template',
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
