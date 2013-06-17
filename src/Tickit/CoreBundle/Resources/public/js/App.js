/**
 * RequireJS config
 */
require.config({
    baseUrl: "/bundles",
    paths: {
        "text": "tickitcore/js/vendor/requirejs-text/text",
        "backbone": "tickitcore/js/vendor/backbone/backbone",
        "marionette": "tickitcore/js/vendor/backbone.marionette/lib/backbone.marionette",
        "underscore": "tickitcore/js/vendor/underscore/underscore",
        "cookie": "tickitcore/js/vendor/cookie/cookie",
        "modules/app": "tickitcore/js/App",
        "modules/core": "tickitcore/js/App.Core",
        "modules/project": "tickitproject/js/App.Project",
        "modules/router": "tickitcore/js/App.Router",
        "modules/template": "tickitcore/js/App.Template",
        "modules/login": "tickituser/js/App.Login",
        "modules/request": "tickitcore/js/App.Request"
    },
    shim : {
        underscore : {
            exports : '_'
        },
        backbone : {
            deps : ['jquery', 'underscore'],
            exports : 'Backbone'
        },
        marionette : {
            deps : ['jquery', 'underscore', 'backbone'],
            exports : 'Backbone.Marionette'
        }
    }
});

/**
 * Base application object.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
require(['backbone', 'underscore', 'marionette', 'tickituser/js/models/Session', 'text'], function(Backbone, _, Marionette, Session) {
    var App = new Marionette.Application();

    App.addRegions({
        mainRegion: '#container',
        navRegion: 'header.main-header',
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