/**
 * Notification view.
 *
 * Provides a view for rendering a notification
 *
 * @type {Marionette.ItemView}
 */
define(['marionette', 'text!notification/views/NotificationView.html'], function(Marionette, tpl) {
    return Marionette.ItemView.extend({
        tagName: 'li'
    });
});
