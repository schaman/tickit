/**
 * Application Navigation functionality.
 *
 * Provides functionality for any navigation components in the application.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
app.nav = {

    /**
     * Initialises navigation.
     *
     * @return {void}
     */
    init : function() {
        this.initProfile();
    },

    /**
     * Profile navigation drop down
     *
     * @return {void}
     */
    initProfile : function() {
        var $menu = $('.profile-menu');

        $('.account > a').on('click', function(e) {
            e.preventDefault();
            if ($menu.height()) {
                close();
            } else {
                open();
            }

            function open() {
                $menu.animate({
                    "height": "150px"
                }, 200);
            }
        });

        function close() {
            $menu.animate({
                "height": 0
            }, 200);
        }

        $('body').on('click', function(e) {
            if (!$(e.target).parents('.account').length) {
                close();
            }
        });
    }

};
