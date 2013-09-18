/**
 * User create view.
 *
 * @type {Backbone.View}
 */
define(['core/js/views/FormView'], function(FormView) {
    return FormView.extend({

        /**
         * Gets the URL for loading the view template
         *
         * @return {void}
         */
        getUrl: function() {
            if (!isNaN(this.id)) {
                return Routing.generate('user_edit_form', { "id": this.id });
            } else {
                return Routing.generate('user_create_form');
            }
        },

        /**
         * Event bindings
         */
        events: {
            "submit" : "onSubmit"
        }
    });
});
