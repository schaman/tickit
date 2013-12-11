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
         * The URL used for fetching new client models
         *
         * @returns {string}
         */
        url: function() {
            return Routing.generate('api_client_list');
        }
    });
});
