/**
 * Settings navigation view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'navigation/js/models/NavigationItem',
    'navigation/js/views/NavigationView',
    'modules/template',
    'text!navigation/views/SettingsNavigation.html'
], function(Marionette, NavigationItem, NavigationView, Template, tpl) {

    Template.load(tpl);

    return Marionette.CompositeView.extend({

        el: '#settings-navigation',
        template: '#settings-navigation-template',
        itemView: NavigationView,
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
            navView.$('ul').append(itemView.el);
        }
    });
});
