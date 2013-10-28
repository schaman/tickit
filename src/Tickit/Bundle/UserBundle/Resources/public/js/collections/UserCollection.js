/**
 * UserCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['user/js/models/User'], function(User) {
    return Backbone.Collection.extend({
        model: User,
        url: function() {
            return Routing.generate('api_user_list');
        }
    });
});
