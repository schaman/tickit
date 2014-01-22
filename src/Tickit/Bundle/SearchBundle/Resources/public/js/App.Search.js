/**
 * Application search module.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define(function() {
    return App.module('search', function(module) {

        var timeout;
        module.on('start', function() {

            /**
             * Hooks into the navigation:ready event.
             *
             * @param {jQuery} $el A jQuery reference to the navigation element
             */
            App.vent.on('navigation:ready', function($el) {
                $el.find('label.icon-search').sidr({
                    name: 'search-side'
                });

                var $searchBox = $el.find('div.search-box');
                $searchBox.on('keyup', function() {
                    if (timeout) {
                        clearTimeout(timeout);
                    }
                    timeout = setTimeout(function() {
                        $.sidr('open', 'search-side');
                    }, 500);
                });

                $('body').on('click', function(e) {
                    var $t = $(e.target);
                    if ($t.parents('#search-side').length || $t.parents('div.search-box').length || $t.is('#search-side')) {
                        return;
                    }

                    $.sidr('close', 'search-side');
                });
            });
        });
    });
});
