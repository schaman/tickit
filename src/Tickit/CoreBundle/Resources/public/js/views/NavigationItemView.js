/**
 * Navigation item view.
 *
 * Provides a view for rendering a single navigation item.
 *
 * @type {Marionette.ItemView}
 */
define(['marionette', 'modules/template', 'text!tickitcore/views/NavigationItem.html'], function(Marionette, Template) {

    Template.loadView(tpl);

    return Marionette.ItemView.extend({
        template: '#navigation_item-template',
        tagName: 'li'
    });
});
