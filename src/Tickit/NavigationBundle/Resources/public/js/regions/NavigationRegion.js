/**
 * The navigation region.
 *
 * Provides a region for displaying navigation items.
 *
 * @type {Marionette.Region}
 */
define(['marionette'], function(Marionette) {
    return Marionette.Region.extend({
        el: '#main',

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
