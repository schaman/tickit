/**
 * Main navigation view.
 *
 * Provides a view object for rendering the main application navigation.
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'tickitnavigation/js/views/NavigationItemView',
    'tickitnavigation/js/views/ProfileNavigationView',
    'modules/user',
    'text!tickitnavigation/views/MainNavigation.html'
], function(Marionette, ItemView, ProfileNavigationView, User, tpl) {

    return Marionette.CompositeView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',
        template: '#navigation_item_collection-template',
        itemView: ItemView,
        profileRegion: null,

        events: {
            "click ul.nav a": "itemClick"
        },

        /**
         * Initialises the view
         *
         * @return {void}
         */
        initialize: function() {
            this.profileRegion = new Backbone.Marionette.Region({
                el: '#account'
            });
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
         * Renders the HTML content of this view
         *
         * @return {Marionette.CompositeView}
         */
        render: function() {
            this.$el.html($(tpl).html());
            var user = User.loadCurrentUser();
            user.on('sync', function() {
                this.profileRegion.show(new ProfileNavigationView({ model: user }));
            }, this);
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
