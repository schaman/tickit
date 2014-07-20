define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({

        defaults: {
            id: null,
            name: ''
        },

        /**
         * Gets the name of this type
         *
         * @return {string}
         */
        getName : function() {
            return this.get('name');
        }

    });
});
