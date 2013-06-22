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
    'modules/template',
    'text!tickitcore/views/ProfileNavigation.html'
], function(Marionette, User, Template, tpl) {

    Template.loadView(tpl);

    return Marionette.ItemView.extend({
        tagName: 'div',
        className: 'account',

        /**
         * Renders the HTML markup for the profile navigation
         *
         * @return {Marionette.ItemView}
         */
        render: function() {
            this.$el.html(_.template($(tpl).html(), this.model.attributes));
            return this;
        }
    });
});
