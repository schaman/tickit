/**
 * Deletable item view.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['marionette', 'modules/messenger'], function(Marionette, Messenger) {
    return Marionette.ItemView.extend({

        /**
         * Deletes the current item
         *
         * @return {void}
         */
        deleteItem : function() {
            var me = this;
            Messenger.confirm(
                'Are you sure you want to delete "' + me.model.toString() + '"',
                function() {
                    me.model.destroy({
                        wait: true
                    });
                },
                'Delete Item'
            );
        }
    });
});
