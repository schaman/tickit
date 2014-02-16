/**
 * User row view template.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define([
    'core/js/views/DeletableItemView',
    'text!user/views/UserRowView.html'
], function(DeletableItemView, tpl) {

    return DeletableItemView.extend({
        template: '#user_row-template',
        tagName: 'tr',

        "events" : {
            "click a.delete-record" : "deleteItem"
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
                isAdmin: this.model.isAdmin(),
                isAdminYesNo: this.model.isAdmin() ? 'Yes' : 'No',
                lastActive: this.model.getLastActive(),
                editUrl: this.model.getEditUrl()
            }));
            return this;
        }
    });
});
