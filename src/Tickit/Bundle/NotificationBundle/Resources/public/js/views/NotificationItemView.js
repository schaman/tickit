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
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                actionUri: d.actionUri,
                message: d.message,
                createdAt: this.model.getCreatedAt()
            }));
            return this;
        }
    });
});
