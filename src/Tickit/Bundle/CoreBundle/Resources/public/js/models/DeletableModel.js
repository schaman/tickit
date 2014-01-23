/**
 * Abstract deletable model.
 *
 * The extending object needs to provide
 * a getDeleteUrl() method.
 *
 * @type {Backbone.Model}
 */
define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({

        /**
         * Overrides the sync action.
         *
         * @param {string} action  The action taking place (e.g. "delete")
         * @param {object} model   The model that is being sync'd
         * @param {object} options The options object for the sync
         */
        sync : function(action, model, options) {
            if (action.toLowerCase() === 'delete') {
                options = options || {};
                options.url = this.getDeleteUrl();
            }

            Backbone.sync(action, model, options);
        }
    });
});
