/**
 * Deletable item view.
 *
 * @type {Backbone.Marionette.ItemView}
 */
define(['marionette'], function(Marionette) {
    return Marionette.ItemView.extend({

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
        }
    });
});
