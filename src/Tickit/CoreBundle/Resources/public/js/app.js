/**
 * Base application object.
 *
 * Provides functionality that is common to the whole application.
 *
 * @author  James Halsall <james.t.halsall@googlemail.com>
 * @license MIT License <http://opensource.org/licenses/MIT>
 */
var app = {

    /**
     * Initialises the application.
     *
     * @return {void}
     */
    init : function() {
        this.nav.init();
    }

};

// make function call to maintain scope inside namespaced functions
$(function() {
    app.init();
});
