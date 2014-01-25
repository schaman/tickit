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
    'navigation/js/regions/ToolbarRegion',
    'jquery',
    'text'
], function(Marionette, Session, MainRegion, AnimatedRegion, ToolbarRegion) {
    var App = new Marionette.Application();

    App.addRegions({
        mainRegion: new MainRegion,
        loginRegion: new AnimatedRegion({ el: '#login-container' }),
        toolbarRegion: new ToolbarRegion,
        notificationRegion: '#notification-side',
        searchRegion: '#search-side'
    });

    window.App = App;
    App.Session = new Session;

    // load any other modules here
    require(['modules/router', 'modules/core', 'modules/template', 'modules/search'], function(Router) {
        App.Router = Router;
        App.start();
    });

    return App;
});