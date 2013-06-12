/**
 * RequireJS config
 */
require.config({
    baseUrl: "/bundles",
    paths: {
        "text": "tickitcore/js/vendor/requirejs-text/text",
        "tpl": "tickitcore/js/vendor/requirejs-tpl/tpl",
        "jquery": "tickitcore/js/vendor/jquery/jquery",
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
        jquery : {
            exports : '$'
        },
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
require(['backbone', 'underscore', 'marionette', 'jquery', 'text', 'tpl'], function(Backbone, _, Marionette) {
    var App = new Marionette.Application();

    App.addRegions({
        mainRegion: '#container',
        navRegion: '', // nav container ID here
        footerRegion: '#footer'
    });

    window.App = App;

    // load any other modules here
    require(['tickituser/js/models/Session', 'modules/router', 'modules/core', 'modules/template'], function(Session) {
        App.start();
        App.Session = new Session;
    });

    return App;
});