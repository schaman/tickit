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
    'modules/template',
    'text!notification/views/NotificationList.html'
], function(Marionette, NotificationView, Template, tpl) {

    Template.load(tpl);

    return Marionette.CompositeView.extend({
        itemView: NotificationView,

        template: '#notification_list-template',

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
