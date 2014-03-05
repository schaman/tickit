/**
 * Issue module
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define(function() {
    return App.module('Issue', function(module) {

        module.startWithParent = false;

        /**
         * Loads issues into the list view
         *
         * @return {void}
         */
        module.loadIssueList = function() {
            require([
                'issue/js/collections/IssueCollection',
                'issue/js/views/IssueListView',
                'filter/js/views/FilterView',
                'issue/js/views/IssueRowView'
            ], function(Collection, ListView, FilterView) {
                var issues = new Collection();
                issues.fetch();

                var view = new ListView({
                    collection: issues,
                    filterFormUrl: Routing.generate('issue_filter_form'),
                    filterViewPrototype: FilterView
                });

                App.mainRegion.show(view);
            });
        }
    });
});
