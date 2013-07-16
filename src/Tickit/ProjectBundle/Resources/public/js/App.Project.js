/**
 * Project module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
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
                'tickitproject/js/collections/ProjectCollection',
                'tickitproject/js/views/ProjectListView',
                'tickitproject/js/views/ProjectRowView'
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
            require(['tickitproject/js/views/ProjectFormView'], function(view) {
                App.mainRegion.show(new view);
            });
        };

        /**
         * Loads edit project view
         *
         * @return {void}
         */
        module.loadProjectEdit = function(id) {
            require(['tickitproject/js/views/ProjectFormView'], function(view) {
                App.mainRegion.show(new view({ id: id }));
            });
        }
    });
});