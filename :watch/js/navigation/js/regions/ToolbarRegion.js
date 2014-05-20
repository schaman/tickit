/**
 * The toolbar region.
 *
 * Provides a region for displaying content in the toolbar.
 *
 * @type {Marionette.Region}
 */
define(['marionette'], function(Marionette) {
    return Marionette.Region.extend({
        el: '#toolbar',

        /**
         * Called whenever a view is displayed inside this region
         *
         * @return {void}
         */
        onShow: function() {
            this.$el.show();
        }
    });
});
