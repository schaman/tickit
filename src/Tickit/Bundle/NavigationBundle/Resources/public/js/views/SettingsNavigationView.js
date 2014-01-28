/**
 * Settings navigation view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'navigation/js/models/NavigationItem',
    'navigation/js/views/NavigationItemView'
], function(Marionette, NavigationItem, ItemView) {

    return Marionette.CollectionView.extend({

        tagName: 'ul',
        itemView: ItemView,
        model: NavigationItem,

        events: {
            "click a": "itemClick"
        },

        /**
         * Triggered after the view has been shown inside a region
         */
        onShow: function() {
            this.collection.fetch();
        },

        /**
         * Appends item HTML to the view
         *
         * @param {Backbone.View} navView  The navigation view
         * @param {Backbone.View} itemView The navigation item view
         */
        appendHtml : function(navView, itemView) {
            navView.$el.append(itemView.el);
        },

        /**
         * Handles navigation item click
         *
         * @param {object} e The event object
         */
        itemClick : function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
        }
    });
});
