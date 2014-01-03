/**
 * List view mixin
 *
 * Provides base functionality for list views in the application.
 *
 * @type {Backbone.View}
 */
define(function() {
    return {

        /**
         * Event bindings for the list view
         */
        events: {
            "click a": "linkClick"
        },

        /**
         * Handles a click event on an <a> tag
         */
        linkClick : function(e) {
            e.preventDefault();
            App.Router.goTo($(e.target).attr('href'));
        }
    };
});