/**
 * Notification listing view.
 *
 * Provides a view object for rendering notifications.
 *
 * @type {Marionette.CollectionView}
 */
define([
    'marionette',
    'notification/js/views/NotificationItemView',
    'text!notification/views/NotificationList.html'
], function(Marionette, NotificationView, tpl) {

    return Marionette.CompositeView.extend({
        itemView: NotificationView,

        /**
         * Event bindings
         */
        events: {
            "click li a span": "itemClick"
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
            App.Router.goTo($(e.target).parent().attr('href'));
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
         * @param {Marionette.CompositeView} notificationView The composite notification view object
         * @param {Marionette.ItemView}      itemView         The navigation item view object
         *
         * @return {void}
         */
        appendHtml: function(notificationView, itemView) {
            notificationView.$('ul').append(itemView.el);
        }
    });
});
