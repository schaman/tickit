/**
 * Notification view.
 *
 * Provides a view for rendering a notification
 *
 * @type {Marionette.ItemView}
 */
define(['marionette', 'text!notification/views/NotificationItem.html'], function(Marionette, tpl) {
    return Marionette.ItemView.extend({
        tagName: 'li',

        /**
         * Renders the HTML markup for the notification
         *
         * @return {Marionette.ItemView}
         */
        render: function() {
            this.$el.html(_.template($(tpl).html(), this.model.attributes));
            return this;
        }
    });
});
