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
    'tickitcore/js/views/ProfileNavigationView',
    'modules/user',
    'text!tickitcore/views/MainNavigation.html'
], function(Marionette, ItemView, ProfileNavigationView, User, tpl) {

    return Marionette.CompositeView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',
        template: '#navigation_item_collection-template',
        itemView: ItemView,
        profileRegion: null,

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
