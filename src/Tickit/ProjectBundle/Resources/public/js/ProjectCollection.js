/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
define(['tickitproject/js/Project'], function() {
    window.ProjectCollection = Backbone.Collection.extend({
        model: Project,
        url: '/projects'
    });
});
