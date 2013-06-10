/**
 * Project module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
App.module('Project', function() {

    this.startWithParent = false;

    /**
     * Loads projects
     */
    this.loadProjectList = function() {
        var projects = new ProjectCollection;
        projects.fetch();
        var view = new ProjectListView({
            collection: projects
        });

        App.mainRegion.show(view);
    }
});