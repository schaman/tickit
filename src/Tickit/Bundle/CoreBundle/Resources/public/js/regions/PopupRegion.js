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
        onShow: function(view) {
            var me = this;
            $.magnificPopup.open({
                removalDelay: 300,
                mainClass: 'my-mfp-slide-bottom',
                closeBtnInside: true,
                items : {
                    src: me.el
                },
                callbacks : {
                    close : function() {
                        view.remove();
                    }
                }
            });
        },

        onClose: function() {
            $.magnificPopup.close();
        }
    });
});
