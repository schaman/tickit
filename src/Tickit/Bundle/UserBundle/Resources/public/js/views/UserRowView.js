/**
 * User row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['modules/template', 'text!user/views/UserRowView.html'], function(Template, tpl) {

    return Backbone.Marionette.ItemView.extend({
        template: '#user_row-template',
        tagName: 'tr',

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
                editUrl: this.model.getEditUrl(),
                deleteUrl: this.model.getDeleteUrl()
            }));
            return this;
        }
    });
});
