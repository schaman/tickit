/**
 * User row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!user/views/UserRowView.html'], function(Template, tpl) {

    return Backbone.Marionette.ItemView.extend({
        template: '#user_row-template',
        tagName: 'tr',

        "events" : {
            "click a.delete-record" : "deleteItem"
        },

        /**
         * Deletes the current item
         *
         * @return {void}
         */
        deleteItem : function() {
            // TODO: need to integrate a confirmation here
            var me = this;
            me.model.destroy({
                wait: true
            });
        },

        /**
         * Renders the template
         */
        render: function() {
            var d = this.model.attributes;
            this.$el.html(_.template($(tpl).html(), {
                id: d.id,
                forename: d.forename,
                surname: d.surname,
                email: d.email,
                username: d.username,
                isAdmin: false,
                lastActive: this.model.getLastActive(),
                editUrl: this.model.getEditUrl()
            }));
            return this;
        }
    });
});
