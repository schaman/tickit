/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'filter/js/views/FilterableListView',
    'project/js/views/ProjectRowView',
    'text!project/views/ProjectListView.html'
], function(Template, FilterableListView, RowView, Tpl) {

    Template.load(Tpl);

    return FilterableListView.extend({
        tagName: 'div',
        template: '#project_list-template',
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
            collectionView.$('#project-list').find('tbody').append(itemView.el);
        }
    });
});
