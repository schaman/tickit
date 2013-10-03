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
        Backbone.history.start({ pushState: true, root: appRoot });
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
        ""                     : "dashboard",
        "dashboard"            : "dashboard",
        "login"                : "login",

        /* ProjectBundle routes */
        "projects/create"      : "projectCreate",
        "projects/edit/:id"    : "projectEdit",
        "projects"             : "projects",

        /* UserBundle routes */
        "users/create"         : "userCreate",
        "users/edit/:id"       : "userEdit",
        "users"                : "users"
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
    },

    "projectCreate" : function() {
        require(['modules/project'], function(Project) {
            Project.loadProjectCreate();
        });
    },

    "projectEdit" : function(id) {
        require(['modules/project'], function(Project) {
            Project.loadProjectEdit(id);
        });
    },

    "users" : function() {
        require(['modules/user'], function(User) {
            User.loadUserList();
        });
    },

    "userCreate" : function() {
        require(['modules/user'], function(User) {
            User.loadUserCreate();
        });
    },

    "userEdit" : function(id) {
        require(['modules/user'], function(User) {
            User.loadUserEdit(id);
        });
    }
});
