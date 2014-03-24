/**
 * Region for displaying views inside a popup.
 *
 * @type {Marionette.Region}
 */
define(['marionette', 'magnific'], function(Marionette) {
    return Marionette.Region.extend({
        el: '#popup-container',

        /**
         * Called whenever a view is displayed inside this region
         *
         * @return {void}
         */
        onShow: function() {
            var me = this;
            $.magnificPopup.open({
                removalDelay: 300,
                mainClass: 'mfp-fade',
                items : {
                    src: me.el
                }
            });
        }
    });
});
