/**
 *
 */
define([
    'marionette',
    'underscore',
    'text!user/views/UserNameWithAvatarView.html'
], function(Marionette, _, tpl) {

    return Marionette.ItemView.extend({

        template: '#user-name-with-avatar-template',

        /**
         * Renders the view
         */
        render : function() {
            var t = this;
            t.$el.html(_.template($(tpl).html(), {
                name: this.model.getFullName(),
                avatarUrl: this.model.get('avatarUrl')
            }));
        }
    });
});
