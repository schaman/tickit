/**
 * Team list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define([
    'modules/template',
    'team/js/views/TeamRowView',
    'text!team/views/TeamListView.html'
], function(Template, rowView, tpl) {

    Template.loadView(tpl);

    return Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'team-list',
        template: '#team_list-template',
        itemView: rowView,

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
