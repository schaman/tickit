/**
 * Profile navigation view.
 *
 * Provides a view object for displaying the profile area of the navigation
 *
 * @type {Marionette.ItemView}
 */
define([
    'marionette',
    'modules/user',
    'text!tickitnavigation/views/ProfileNavigation.html'
], function(Marionette, User, tpl) {

    return Marionette.ItemView.extend({
        tagName: 'div',
        template: 'profile_navigation-template',
        className: 'navbar-inner',

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
        }
    });
});
