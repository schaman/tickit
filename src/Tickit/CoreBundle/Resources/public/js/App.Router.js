/**
 * Application router.
 *
 * @type {Backbone.Router}
 */
var AppRouter = Backbone.Router.extend({

    /**
     * Router initialize
     */
    initialize : function() {
        Backbone.history.start({ pushState: true });
    },

    /**
     * Route patterns
     */
    routes : {
        ""          : "dashboard",
        "dashboard" : "dashboard",
        "login"     : "login",
        "projects"  : "projects"
    },

    "login" : function() {
        consoloe.log('login init');
    },

    "dashboard" : function() {
        console.log('dashboard init');
    },

    "projects" : function() {
        require(['modules/project'], function() {
            App.Project.loadProjectList();
        });
    }
});

App.Router = new AppRouter;