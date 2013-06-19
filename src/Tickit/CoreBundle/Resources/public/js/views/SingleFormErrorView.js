/**
 * Single form error view.
 *
 * Provides a view object for rendering a single form error
 *
 * @type {Marionette.ItemView}
 */
define(['modules/template', 'text!tickitcore/views/FormErrorSingle.html'], function(Template, tpl) {

    Template.loadView(tpl);

    return Backbone.Marionette.ItemView.extend({
        template: '#form_error_single-template',
        tagName: 'div',
        className: 'alert alert-error'
    });
});
