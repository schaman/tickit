/**
 * ClientCollection
 *
 * @type {Backbone.Collection.extend}
 */
define([
    'client/js/models/Client',
    'filter/js/collections/FilterableCollection'
], function(Client, FilterableCollection) {
    return FilterableCollection.extend({

        /**
         * The model type that this collection manages
         *
         * @type {Backbone.Model}
         */
        model: Client,

        /**
         * Gets the route name for this collection data
         *
         * @return {string}
         */
        getRouteName : function() {
            return 'api_client_list';
        }
    });
});
