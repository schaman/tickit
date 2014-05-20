/**
 * Issue module
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
define(function() {
    return App.module('Issue', function(module) {

        module.startWithParent = true;

        module.on('start', function() {
            // after the navigation has been initialised we want
            // to bind any custom navigation actions relevant for
            // this module
            App.vent.on('navigation:ready', function($el) {
                $el.on('click', 'a.add-ticket', function() {
                    module.loadIssueCreate();
                });
            });
        });

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
        };

        /**
         * Loads create issue view
         *
         * @return {void}
         */
        module.loadIssueCreate = function() {
            require(['issue/js/views/IssueFormView'], function(view) {
                App.popupRegion.show(new view());
            });
        };

        /**
         * Loads edit issue view
         *
         * @return {void}
         */
        module.loadIssueEdit = function(id) {
            require(['issue/js/views/IssueFormView'], function(view) {
                App.mainRegion.show(new view({ id: id }));
            });
        }
    });
});
