/**
 * ClientCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['client/js/models/Client', '../../../../../../../.'], function(Client, Backbone) {
    return Backbone.Collection.extend({
        model: Client,
        url: function() {
            return Routing.generate('api_client_list');
        }
    });
});
