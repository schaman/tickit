/**
 * Project Attributes form helpers.
 *
 * This object provides functionality for attribute forms in the project bundle
 *
 * @author  James Halsall <james.t.halsall@googlemail.coM>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
app.project.attributes.form = {

    /**
     * Initialises attribute forms.
     *
     * @return {void}
     */
    init : function() {
        this.bindChoiceButton();
    },

    /**
     * Binds click event to the "Add Choice" button.
     *
     * This method is for the ChoiceAttributeFormType
     */
    bindChoiceButton : function() {
        var $choices = $('#choice-attribute-choices');
        var count = $choices.find('input').length;
        $choices.find('a').on('click', function(e) {
            e.preventDefault();
            var row = $choices.data('prototype').replace(/__name__/g, count++);
            $choices.append(row);
        });
    }
};

$(function() {
    app.project.attributes.form.init();
});