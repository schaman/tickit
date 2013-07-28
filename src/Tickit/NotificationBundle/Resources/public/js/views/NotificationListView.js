/**
 * Notification listing view.
 *
 * Provides a view object for rendering notifications.
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'notification/js/views/NotificationView',
    'text!notification/views/NotificationList.html'
], function(Marionette, NotificationView, tpl) {

    return Marionette.CompositeView.extend({
        tagName: 'div',
        className: 'notifications',
        itemView: NotificationView,

        events: {
            // TODO
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
            navView.$('ul').append(itemView.el);
        }
    });
});
