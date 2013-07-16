/**
 * Project module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
define(function(collection, listView) {
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
            ], function() {
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

        };

        /**
         * Loads edit project view
         *
         * @return {void}
         */
        module.loadProjectEdit = function() {

        }
    });
});