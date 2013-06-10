/**
 * Project module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
define([
    'tickitproject/js/Project',
    'tickitproject/js/ProjectCollection',
    'tickitproject/js/ProjectListView',
    'tickitproject/js/ProjectRowView'
], function() {
    App.module('Project', function(module) {

        module.startWithParent = false;

        /**
         * Loads projects
         */
        module.loadProjectList = function() {
            var projects = new ProjectCollection;
            projects.fetch();
            var view = new ProjectListView({
                collection: projects
            });

            App.mainRegion.show(view);
        }
    });
});