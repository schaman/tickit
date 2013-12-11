/**
 * UserCollection
 *
 * @type {Backbone.Collection.extend}
 */
define([
    'user/js/models/User',
    'filter/js/collections/FilterableCollection'
], function(User, FilterableCollection) {
    return FilterableCollection.extend({

        /**
         * The model type that this collection manages
         *
         * @type {Backbone.Model}
         */
        model: User,

        /**
         * The URL used for fetching user models
         *
         * @returns {string}
         */
        url: function() {
            return Routing.generate('api_user_list');
        }
    });
});
