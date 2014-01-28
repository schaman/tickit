define([
    'marionette',
    'modules/template',
    'text!navigation/views/Toolbar.html'
], function(Marionette, Template, tpl) {

    Template.load(tpl);

    return Marionette.Layout.extend({
        template: '#toolbar-layout-template',
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
        }
    });
});
