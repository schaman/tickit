/**
 * RequireJS config
 */
require.config({
    baseUrl: "/bundles",
    paths: {
        "jquery": "tickitcore/js/vendor/jquery/jquery",
        "jquery-ui": "/js/vendor/jquery-ui-1.10.3.min",
        "text": "tickitcore/js/vendor/requirejs-text/text",
        "backbone": "tickitcore/js/vendor/backbone/backbone",
        "marionette": "tickitcore/js/vendor/backbone.marionette/lib/backbone.marionette",
        "underscore": "tickitcore/js/vendor/underscore/underscore",
        "cookie": "tickitcore/js/vendor/cookie/cookie",
        "bootstrap-js": "/js/bootstrap.min",
        "bootstrap-switch": "/js/bootstrap-switch",
        "bootstrap-select": "/js/bootstrap-select",
        "flatui-checkbox": "/js/flatui-checkbox",
        "flatui-radio": "/js/flatui-radio",
        "jquery-placeholder": "/js/jquery.placeholder",
        "jquery-tagsinput": "/js/jquery.tagsinput",
        "modules/app": "tickitcore/js/App",
        "modules/core": "tickitcore/js/App.Core",
        "modules/project": "tickitproject/js/App.Project",
        "modules/router": "tickitcore/js/App.Router",
        "modules/template": "tickitcore/js/App.Template",
        "modules/login": "tickituser/js/App.Login",
        "modules/request": "tickitcore/js/App.Request"
    },
    shim : {
        "jquery": {
            exports: '$'
        },
        "underscore" : {
            exports : '_'
        },
        "backbone" : {
            deps : ['jquery', 'underscore'],
            exports : 'Backbone'
        },
        "marionette" : {
            deps : ['jquery', 'underscore', 'backbone'],
            exports : 'Backbone.Marionette'
        },
        "jquery-ui": ['jquery'],
        "bootstrap-js":  ['jquery'],
        "bootstrap-switch": ['bootstrap-js'],
        "bootstrap-select": ['bootstrap-js'],
        "flatui-checkbox": ['jquery'],
        "flatui-radio": ['jquery'],
        "jquery-placeholder": ['jquery'],
        "jquery-tagsinput": ['jquery']
    }
});

/**
 * Base application object.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
require([
    'marionette',
    'tickituser/js/models/Session',
    'jquery-ui',
    'bootstrap-select',
    'bootstrap-switch',
    'flatui-checkbox',
    'flatui-radio',
    'jquery-placeholder',
    'jquery-tagsinput',
    'text'
], function(Marionette, Session) {
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