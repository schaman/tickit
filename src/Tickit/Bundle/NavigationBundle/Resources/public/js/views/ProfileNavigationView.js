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

            // TODO: eventually this will be dispatched via App.vent
            var timeout;
            var $searchBox = this.$el.find('div.search-box');
            $searchBox.on('keyup', function() {
                if (timeout) {
                    clearTimeout(timeout);
                }
                timeout = setTimeout(search, 500);

                function search() {
                    $.sidr('open', 'search-side');
                }
            });

            $('body').on('click', function(e) {
                if ($(e.target).parents('#search-side') || $(e.target).parents('div.search-box'))  {
                    return;
                }

                $.sidr('close', 'search-side');
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
