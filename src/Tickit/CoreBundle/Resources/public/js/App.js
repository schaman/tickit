/**
 * Base application object.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
require([
    'marionette',
    'user/js/models/Session',
    'core/js/regions/MainRegion',
    'core/js/regions/AnimatedRegion',
    'navigation/js/regions/NavigationRegion',
    'jquery',
    'jqueryui',
    'bootstrapselect',
    'bootstrapswitch',
    'flatuicheckbox',
    'flatuiradio',
    'jqueryplaceholder',
    'jquerytagsinput',
    'text'
], function(Marionette, Session, MainRegion, AnimatedRegion, NavigationRegion) {
    var App = new Marionette.Application();

    App.addRegions({
        mainRegion: new MainRegion,
        loginRegion: new AnimatedRegion({ el: '#container' }),
        navRegion: new NavigationRegion,
        toolbarRegion: 'header.main-header',
        notificationRegion: '#notification-side',
        footerRegion: '#footer'
    });

    window.App = App;
    App.Session = new Session;

    // load any other modules here
    require(['modules/router', 'modules/core', 'modules/template'], function() {
        App.start();
    });

    return App;
});