define([
    'marionette',
    'text!navigation/views/Toolbar.html',
    'underscore'
], function(Marionette, tpl, _) {

    return Marionette.Layout.extend({
        template: _.template($(tpl).html(), {
            logout: Routing.generate('fos_user_security_logout')
        }),
        className: 'navbar navbar-inverse',

        /**
         * Regions managed by this layout
         *
         * @type {object}
         */
        regions : {
            navRegion : 'div.quick-links',
            profileRegion : 'div.account-thumb',
            settingsNavRegion: '#settings-navigation div'
        },

        /**
         * Fired after the layout has been shown inside a region
         */
        onShow : function() {
            App.vent.trigger('navigation:ready', this.$el);
        },

        /**
         * Fired when the layout has been rendered
         */
        onRender : function() {
            var $loader = this.$('div.loader');
            App.vent.on('loading:start', function() {
                $loader.css('display', 'inline-block');
            });

            App.vent.on('loading:complete', function() {
                $loader.hide();
            });
        }
    });
});
