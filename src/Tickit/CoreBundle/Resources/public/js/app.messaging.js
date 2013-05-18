/**
 * Application messaging helpers.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
app.messaging = {

    /**
     * Displays a notification to the user
     *
     * @param {String} title   The title of the notification
     * @param {String} message The message body for the notification
     */
    notify : function(title, message) {
        alert(message);
    },

    /**
     * Displays an error message to the user
     *
     * @param {String} title   The title of the error
     * @param {String} message The message body for the error
     */
    error : function(title, message) {
        alert(message);
    }
};
