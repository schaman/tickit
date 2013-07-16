/**
 * Project create view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'modules/request'], function(Backbone, Request) {
    return Backbone.View.extend({
        tagName: 'div',

        render: function() {
            Request.get({
                url: Routing.generate('project_create_form'),
                success: function(resp) {
                    this.$el.html(resp);
                }
            });
        }
    });
});
