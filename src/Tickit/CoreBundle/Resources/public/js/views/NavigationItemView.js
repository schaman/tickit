/**
 * Navigation item view.
 *
 * Provides a view for rendering a single navigation item.
 *
 * @type {Marionette.ItemView}
 */
define(['marionette', 'text!tickitcore/views/NavigationItem.html'], function(Marionette, tpl) {
    return Marionette.ItemView.extend({
        template: function(data) {
            return _.template($(tpl).html(), {
                name: data.name,
                uri: data.uri,
                active: data.active
            });
        },
        tagName: 'li'
    });
});
