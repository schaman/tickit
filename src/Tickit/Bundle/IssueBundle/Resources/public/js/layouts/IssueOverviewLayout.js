/**
 * Issue overview layout.
 *
 * Provides a layout for rendering regions of views for the
 * issue overview view.
 */
define([
    'marionette',
    'modules/template',
    'text!issue/views/IssueOverviewLayout.html'
], function(Marionette, Template, tpl) {

    Template.load(tpl);

    return Marionette.Layout.extend({

        template: '#issue-overview-layout-template',

        /**
         * Regions managed by this layout
         *
         * @type {object}
         */
        regions: {
            infoRegion: '#basic-info',
            tabRegion: '#tabs-container',
            featureRegion: '#feature-container'
        }
    });
});
