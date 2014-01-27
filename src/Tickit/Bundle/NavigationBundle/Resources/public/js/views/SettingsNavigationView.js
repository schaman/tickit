/**
 * Settings navigation view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'navigation/js/models/NavigationItem',
    'navigation/js/views/NavigationView',
    'text!navigation/views/SettingsNavigation.html'
], function(Marionette, NavigationItem, NavigationView, tpl) {

    return Marionette.CompositeView.extend({

        itemView: NavigationView,

        events: {
            "click a": "itemClick"
        },

        render : function() {
            this.$el.html($(tpl).html());
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
            navView.$('ul').append(itemView.el);
        }
    });
});
