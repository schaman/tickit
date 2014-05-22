/**
 * Mixin for initialising select elements
 *
 * @type {object}
 */
define(['select2'], function() {
    return {
        /**
         * Initialises select2 on the select elements
         */
        initSelects : function() {
            var $selects = this.$el.find('select');
            _.each($selects, function(el) {
                $(el).select2();
            });
        }
    };
});
