/**
 * Single form error view.
 *
 * Provides a view object for rendering a single form error
 *
 * @type {Marionette.ItemView}
 */
define(['modules/template', 'text!core/views/FormErrorSingle.html'], function(Template, tpl) {

    return Backbone.Marionette.ItemView.extend({
        template: Template.load(tpl),
        tagName: 'div',
        className: 'alert alert-error'
    });
});
