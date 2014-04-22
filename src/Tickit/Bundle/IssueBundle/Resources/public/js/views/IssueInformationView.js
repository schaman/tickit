/**
 * Basic issue information view
 *
 * @type {Backbone.View}
 */
define([
    'marionette',
    'text!issue/views/IssueInformationView.html',
    'underscore'
], function(Marionette, tpl, _) {
    return Marionette.ItemView.extend({
        template: '#issue-information-template',

        /**
         * Renders the view
         */
        render: function() {
            var m = this.model;
            this.$el.html(_.template($(tpl).html(), {
                id: m.get('id'),
                number: m.get('number'),
                title: m.get('title'),
                type: m.get('type'),
                status: m.get('status'),
                priority: m.get('priority')
            }));

            return this;
        }
    });
});
