/**
 * Abstract form view.
 *
 * @type {Backbone.View}
 */
define([
    'backbone',
    'modules/request',
    'modules/template',
    'picker/js/views/PickerInitialiseMixin'
], function(Backbone, Request, Template, PickerMixin) {
    var view = Backbone.View.extend({
        /**
         * Event bindings
         */
        events: {
            "submit" : "onSubmit"
        },

        /**
         * Handles a submit event
         *
         * @return {void}
         */
        onSubmit : function(e) {
            var me = this;
            e.preventDefault();
            var $form = $(e.target).closest('form');

            Request.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                success: function(resp) {
                    if (resp.success) {
                        App.Router.goTo(resp.returnUrl);
                    } else {
                        me.$el.html(resp.form);
                    }
                }
            });

        },

        /**
         * Renders the view
         *
         * @returns {Backbone.View}
         */
        render: function() {
            var t = this;
            var forceFetch = (typeof this.id != 'undefined');
            Template.fetch(this.getUrl(), function(tpl) {
                t.$el.html(tpl);
                t.initPickers.apply(t);
            }, forceFetch);

            return this;
        }
    });

    _.extend(view.prototype, PickerMixin);

    return view;
});
