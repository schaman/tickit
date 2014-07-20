/**
 * Profile navigation view.
 *
 * Provides a view object for displaying the profile area of the navigation
 *
 * @type {Marionette.ItemView}
 */
define([
    'marionette',
    'text!navigation/views/ProfileNavigation.html'
], function(Marionette, tpl) {

    return Marionette.ItemView.extend({

        tagName: 'a',

        /**
         * Renders the HTML markup for the profile navigation
         *
         * @return {Marionette.ItemView}
         */
        render: function() {
            this.$el.html(_.template($(tpl).html(), {
                avatarUrl: this.model.get('avatarUrl')
            }, { variable: 'user' }));
            return this;
        }
    });
});
