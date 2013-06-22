/**
 * Main navigation view.
 *
 * Provides a view object for rendering the main application navigation.
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'modules/template',
    'tickitcore/js/views/NavigationItemView',
    'text!tickitcore/views/MainNavigation.html'
], function(Marionette, Template, ItemView, tpl) {

    Template.loadView(tpl);

    return Marionette.CompositeView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',
        template: '#navigation_item_collection-template',
        itemView: ItemView,

        appendHtml : function(navView, itemView) {
            navView.$('ul.nav').append(itemView.el);
        }
    });
});
