/**
 * Main navigation view.
 *
 * Provides a view object for rendering navigation items in the toolbar
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'navigation/js/views/NavigationItemView',
    'navigation/js/models/NavigationItem'
], function(Marionette, ItemView, NavigationItem) {

    return Marionette.CollectionView.extend({
        tagName: 'ul',
        className: 'nav',
        itemView: ItemView,
        model: NavigationItem,

        events: {
            "click a": "itemClick"
        },

        /**
         * Triggers sidr integration after the view has been rendered
         */
        onShow: function() {
            App.vent.trigger('navigation:ready', this.$el);
            this.collection.fetch();
        },

        /**
         * Handles a navigation item click
         *
         * @param {object} e The event object
         *
         * @return {void}
         */
        itemClick: function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
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
            navView.$el.append(itemView.el);
        }
    });
});
