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
            profileRegion : 'div.account'
        }
    });
});
