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
         * Gets the route name for this collection data
         *
         * @return {string}
         */
        getRouteName : function() {
            return 'api_user_list';
        }
    });
});
