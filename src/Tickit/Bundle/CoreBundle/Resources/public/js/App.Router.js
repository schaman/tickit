/**
 * Application router.
 *
 * Responsible for routing URLs to application actions
 *
 * @type {Backbone.Router}
 */
define(function() {
    var Router = Backbone.Router.extend({

        /**
         * Router initialize
         */
        initialize : function() {
            Backbone.history.start({ pushState: true, root: this.getAppRoot() });
        },

        /**
         * Gets the app root based off the environment
         *
         * @return {string}
         */
        getAppRoot : function() {
            if ($.cookie('env') === 'test') {
                return '/app_test.php/';
            }

            return '/';
        },

        /**
         * Navigates the client to a path and triggers the change event
         *
         * @param {string} path The path to navigate to
         *
         * @return {void}
         */
        goTo : function(path) {
            path = path || '';
            if (path.match(/^javascript:/)) {
                return;
            }

            this.navigate(path.replace(this.getAppRoot(), '/'), { trigger: true });
        },

        /**
         * Route patterns
         */
        routes : {
            ""                     : "dashboard",
            "dashboard"            : "dashboard",
            "login"                : "login",

            /* ClientBundle routes */
            "clients/create"       : "clientCreate",
            "clients/edit/:id"     : "clientEdit",
            "clients"              : "clients",

            /* IssueBundle routes */
            "issues"               : "issues",

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

        "clientCreate" : function() {
            require(['modules/client'], function(Client) {
                Client.loadClientCreate();
            });
        },

        "clientEdit" : function(id) {
            require(['modules/client'], function(Client) {
                Client.loadClientEdit(id);
            });
        },

        "clients" : function() {
            require(['modules/client'], function(Client) {
                Client.loadClientList();
            });
        },

        "issues" : function() {
            require(['modules/issue'], function(Issue) {
                Issue.loadIssueList();
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

    return new Router();
});
