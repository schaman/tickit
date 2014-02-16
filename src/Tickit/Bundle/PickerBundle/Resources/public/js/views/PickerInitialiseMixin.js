/**
 * Mixin for initialising picker fields
 *
 * @type {object}
 */
define(['modules/request', 'select2'], function(Request) {
    return {
        /**
         * Initialises the select2 integration
         */
        initPickers : function() {
            var $pickers = this.$el.find('.picker');
            _.each($pickers, function(el) {
                var $e = $(el);
                var maxSelections = $e.data('max-selections') || false;
                var options = {
                    multiple: true,
                    minimumInputLength: 3,
                    query: function(query) {
                        Request.get({
                            url: Routing.generate($e.data('provider'), { term: query.term }),
                            success: function(resp) {
                                query.callback({
                                    results: resp
                                });
                            }
                        });
                    },
                    initSelection: function (selectElement, callback) {
                        var initialValue = JSON.parse(selectElement.val());
                        selectElement.val('');
                        callback(initialValue);
                    }
                };
                if (maxSelections !== false) {
                    options.maximumSelectionSize = maxSelections;
                }
                $e.select2(options);
            });
        }
    };
});
