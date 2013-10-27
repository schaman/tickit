/**
 * Project create view.
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
                return Routing.generate('project_edit_form', { "id": this.id });
            } else {
                return Routing.generate('project_create_form');
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
