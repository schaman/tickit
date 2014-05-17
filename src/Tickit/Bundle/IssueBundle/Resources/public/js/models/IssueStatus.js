define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({

        defaults: {
            id: null,
            name: '',
            color: ''
        },

        /**
         * Gets the name of this issue
         *
         * @return {string}
         */
        getName : function() {
            return this.get('name');
        }

    });
});
