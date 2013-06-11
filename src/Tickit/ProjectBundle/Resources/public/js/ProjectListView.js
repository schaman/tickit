/**
 * Project list view.
 *
 * @type {Backbone.Marionette.CompositeView}
 */
define(['tickitproject/js/ProjectRowView', 'text!tickitproject/views/ProjectListView.html'], function(rowView, tpl) {

    //TODO: load template into dom via a helper
    $('body').append($(tpl));

    return Backbone.Marionette.CompositeView.extend({
        tagName: 'table',
        id: 'project-list',
        template: '#project_list-template',
        itemView: rowView,

        appendHtml: function(collectionView, itemView) {
            collectionView.$('tbody').append(itemView.el);
        }
    });
});
