define(['modules/app'], function(App) {
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
            "projects"  : "projects"
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
});
