/**
 * Client module
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 */
define(function() {
    return App.module('Client', function(module) {

        module.startWithParent = false;

        /**
         * Loads clients into the list view
         *
         * @return {void}
         */
        module.loadClientList = function() {
            require([
                'client/js/collections/ClientCollection',
                'filter/js/views/FilterView',
                'client/js/views/ClientListView',
                'client/js/views/ClientRowView'
            ], function(Collection, FilterView, ListView) {
                var clients = new Collection;
                clients.fetch();
                var view = new ListView({
                    collection: clients,
                    filterViewPrototype: FilterView,
                    filterFormUrl: Routing.generate('client_filter_form')
                });

                App.mainRegion.show(view);
            });
        };

        /**
         * Loads a client create view
         *
         * @return {void}
         */
        module.loadClientCreate = function() {
            require(['client/js/views/ClientFormView'], function(view) {
                App.mainRegion.show(new view);
            });
        };

        /**
         * Loads a client edit view
         *
         * @param {Number} id The client ID
         *
         * @return {void}
         */
        module.loadClientEdit = function(id) {
            require(['client/js/views/ClientFormView'], function(view) {
                App.mainRegion.show(new view({ id: id }));
            });
        }
    });
});
