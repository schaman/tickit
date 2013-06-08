/**
 * ProjectCollection
 *
 * @type {Backbone.Collection.extend}
 */
var ProjectCollection = Backbone.Collection.extend({
    model: Project,
    url: '/projects'
});