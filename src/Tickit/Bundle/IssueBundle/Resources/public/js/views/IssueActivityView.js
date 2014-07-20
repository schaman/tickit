/**
 * Issue activity feed view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'text!issue/views/IssueActivityView.html'], function(Backbone, tpl) {
    return Backbone.View.extend({
        template: '#issue-activity-template',

        /**
         * Renders the view
         */
        render: function() {
            this.$el.html($(tpl).html());

            return this;
        }
    });
});
