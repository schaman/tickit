/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
var ProjectCollection;
define(['tickitproject/js/Project'], function() {
    ProjectCollection = Backbone.Collection.extend({
        model: Project,
        url: '/projects'
    });
});
