/**
 * Tickit specific theme for noty.
 *
 * @type {object}
 */
define(['jquery', 'noty'], function($) {
    return $.noty.themes.tickit = {
        name: 'notification',
        modal: {
            css: {
                position: 'fixed',
                width: '100%',
                height: '100%',
                backgroundColor: '#000',
                zIndex: 10000,
                opacity: 0.6,
                display: 'none',
                left: 0,
                top: 0
            }
        }
    };
});
