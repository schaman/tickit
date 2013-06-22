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
], function(Marionette, ItemView, tpl) {

    return Marionette.CompositeView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',
        template: '#navigation_item_collection-template',
        itemView: ItemView,

        /**
         * Renders the HTML content of this view
         *
         * @return {Marionette.CompositeView}
         */
        render: function() {
            var $tpl = $(tpl);
            this.$el.html($tpl.html());
            return this;
        },

        /**
         * Method used to append collection items to this view
         *
         * @param {Marionette.CompositeView} navView  The composite navigation view object
         * @param {Marionette.ItemView}      itemView The navigation item view object
         *
         * @return {void}
         */
        appendHtml: function(navView, itemView) {
            navView.$('ul.nav').append(itemView.el);
        }
    });
});
