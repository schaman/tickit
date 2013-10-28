/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['project/js/models/Project'], function(Project) {
    return Backbone.Collection.extend({
        model: Project,
        url: function() {
            return Routing.generate('api_project_list');
        }
    });
});
