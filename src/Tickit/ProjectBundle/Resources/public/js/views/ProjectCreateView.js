/**
 * Project create view.
 *
 * @type {Backbone.View}
 */
define(['backbone', 'modules/request'], function(Backbone, Request) {
    return Backbone.View.extend({
        render: function() {
            var me = this;
            Request.get({
                url: Routing.generate('project_create_form'),
                success: function(resp) {
                    me.$el.html(resp);
                }
            });
            return this;
        }
    });
});
