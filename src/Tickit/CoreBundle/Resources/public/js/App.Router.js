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
        Backbone.history.start({ pushState: false });
    },

    /**
     * Route patterns
     */
    routes : {
        "/"         : "dashboard",
        "dashboard" : "dashboard",
        "login"     : "login",
        "projects"  : "projects"
    },

    "login" : function() {
        require(['modules/login'], function(Login) {
            Login.loadLoginView();
        });
    },

    "dashboard" : function() {
        console.log('dashboard init');
    },

    "projects" : function() {
        require(['modules/project'], function(Project) {
            Project.loadProjectList();
        });
    }
});

App.Router = new AppRouter;