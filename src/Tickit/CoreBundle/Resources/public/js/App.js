/**
 * Base application object.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
require([
    'marionette',
    'tickituser/js/models/Session',
    'tickitcore/js/regions/MainRegion',
    'tickitcore/js/regions/AnimatedRegion',
    'tickitnavigation/js/regions/NavigationRegion',
    'jquery-ui',
    'bootstrap-select',
    'bootstrap-switch',
    'flatui-checkbox',
    'flatui-radio',
    'jquery-placeholder',
    'jquery-tagsinput',
    'text'
], function(Marionette, Session, MainRegion, AnimatedRegion, NavigationRegion) {
    var App = new Marionette.Application();

    App.addRegions({
        mainRegion: new MainRegion,
        loginRegion: new AnimatedRegion({ el: '#container' }),
        navRegion: new NavigationRegion,
        toolbarRegion: 'header.main-header',
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