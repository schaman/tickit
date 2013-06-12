/**
 * Project module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
define([
    'tickitproject/js/models/Project',
    'tickitproject/js/collections/ProjectCollection',
    'tickitproject/js/views/ProjectListView',
    'tickitproject/js/views/ProjectRowView'
], function(Project, collection, listView) {
    App.module('Project', function(module) {

        module.startWithParent = false;

        /**
         * Loads projects into the list view
         *
         * @return {void}
         */
        module.loadProjectList = function() {
            var projects = new collection;
            projects.fetch();
            var view = new listView({
                collection: projects
            });

            App.mainRegion.show(view);
        }
    });

    return App.Project;
});