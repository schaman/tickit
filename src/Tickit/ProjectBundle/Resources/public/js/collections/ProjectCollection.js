/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['tickitproject/js/models/Project'], function(Project) {
    return Backbone.Collection.extend({
        model: Project,
        url: '/projects'
    });
});
