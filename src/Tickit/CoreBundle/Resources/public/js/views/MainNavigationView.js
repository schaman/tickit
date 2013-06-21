/**
 * Main navigation view.
 *
 * Provides a view object for rendering the main application navigation.
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'tickitcore/js/views/NavigationItemView',
    'text!tickitcore/views/MainNavigation.html'
], function(Marionette, ItemView) {
    return Marionette.CollectionView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',
        template: '#navigation_item_collection-template',
        itemView: ItemView,

        appendHtml : function(navView, itemView) {
            navView.$('ul.nav').append(itemView.el);
        }
    });
});
