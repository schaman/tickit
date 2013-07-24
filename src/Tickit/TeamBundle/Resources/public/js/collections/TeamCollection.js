/**
 * TeamCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['team/js/models/Team'], function(Team) {
    return Backbone.Collection.extend({
        model: Team,
        url: function() {
            return Routing.generate('api_team_list');
        }
    });
});
