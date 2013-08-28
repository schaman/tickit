/**
 * Team module
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
define(function() {
    return App.module('Team', function(module) {

        module.startWithParent = false;

        /**
         * Loads teams into the list view
         *
         * @return {void}
         */
        module.loadTeamList = function() {
            require([
                'team/js/collections/TeamCollection',
                'team/js/views/TeamListView',
            ], function(collection, listView) {
                var teams = new collection;
                teams.fetch();
                var view = new listView({
                    collection: teams
                });

                App.mainRegion.show(view);
            });
        };

        /**
         * Loads create team view
         *
         * @return {void}
         */
        module.loadTeamCreate = function() {
//            require(['team/js/views/TeamFormView'], function(view) {
//                App.mainRegion.show(new view);
//            });
        };

        /**
         * Loads edit team view
         *
         * @return {void}
         */
        module.loadTeamEdit = function(id) {
//            require(['team/js/views/TeamFormView'], function(view) {
//                App.mainRegion.show(new view({ id: id }));
//            });
        }
    });
});