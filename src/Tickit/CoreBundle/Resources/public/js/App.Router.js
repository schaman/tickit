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
     * Navigates the client to a path and triggers the change event
     *
     * @param {string} path The path to navigate to
     *
     * @return {void}
     */
    goTo : function(path) {
        this.navigate(path, { trigger: true });
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
        require(['modules/login'], function(Login) {
            Login.loadLoginView();
        });
    },

    "dashboard" : function() {
        require(['modules/dashboard'], function(Dashboard) {
            Dashboard.loadDashboard();
        });
    },

    "projects" : function() {
        require(['modules/project'], function(Project) {
            Project.loadProjectList();
        });
    }
});

App.Router = new AppRouter;