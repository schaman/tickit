/**
 * Navigation item model.
 *
 * Represents an item in the navigation
 *
 * @type {Backbone.Model}
 */
define(['backbone'], function(Backbone) {
    return Backbone.Model.extend({
        defaults: {
            name: '',
            icon: 'default',
            routeName: ''
        },

        /**
         * Gets the URI for this item based off its route name
         *
         * @return {string}
         */
        getUri: function() {
            return Routing.generate(this.get('routeName'));
        },

        /**
         * Gets the icon name for this nav item
         *
         * @return {string}
         */
        getIconName : function() {
            var icon = this.get('icon');
            if (icon === '') {
                return 'default';
            }

            return icon;
        }
    });
});
