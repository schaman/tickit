/**
 * Project module
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define(function() {
    return App.module('Project', function(module) {

        module.startWithParent = false;

        /**
         * Loads projects into the list view
         *
         * @return {void}
         */
        module.loadProjectList = function() {
            require([
                'project/js/collections/ProjectCollection',
                'project/js/views/ProjectListView',
                'project/js/views/ProjectRowView'
            ], function(collection, listView) {
                var projects = new collection;
                projects.fetch();
                var view = new listView({
                    collection: projects
                });

                App.mainRegion.show(view);
            });
        };

        /**
         * Loads create project view
         *
         * @return {void}
         */
        module.loadProjectCreate = function() {
            require(['project/js/views/ProjectFormView'], function(view) {
                App.mainRegion.show(new view);
            });
        };

        /**
         * Loads edit project view
         *
         * @return {void}
         */
        module.loadProjectEdit = function(id) {
            require(['project/js/views/ProjectFormView'], function(view) {
                App.mainRegion.show(new view({ id: id }));
            });
        }
    });
});