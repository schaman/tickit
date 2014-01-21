/**
 * Profile navigation view.
 *
 * Provides a view object for displaying the profile area of the navigation
 *
 * @type {Marionette.ItemView}
 */
define([
    'marionette',
    'text!navigation/views/ProfileNavigation.html',
    'sidr'
], function(Marionette, tpl) {

    return Marionette.ItemView.extend({
        tagName: 'div',
        className: 'navbar navbar-inverse',

        /**
         * Event bindings
         */
        events: {
            "click #logo": "logoClick"
        },

        /**
         * Triggers sidr integration after the view has been rendered
         */
        onShow: function() {
            this.$el.find('#notification').sidr({
                name: 'notification-side',
                side: 'right'
            });

            this.$el.find('#search').sidr({
                // TODO: implement this in a way that it only triggers when keying in a search term
            });
        },

        /**
         * Renders the HTML markup for the profile navigation
         *
         * @return {Marionette.ItemView}
         */
        render: function() {
            var d = this.model.attributes;

            this.$el.html(_.template($(tpl).html(), {
                fullname: this.model.getFullName(),
                avatarUrl: d.avatarUrl
            }, { variable: 'user' }));
            return this;
        },

        /**
         * Handles a click event on the logo
         */
        logoClick: function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
        }
    });
});
