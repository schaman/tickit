/**
 * User application helpers.
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 * @license MIT <http://opensource.org/licenses/MIT>
 */
app.user.form = {

    /**
     * Initialiser
     */
    init : function() {
        this.bindGroupSelect();
        this.bindUserOverridePermissionToggle();
    },

    /**
     * Binds change event to the group select button in the user form.
     *
     * When this button changes, it should reload permissions for the user based
     * off the new group.
     *
     * @return {void}
     */
    bindGroupSelect : function() {
        $('#tickit_user_group').on('change', function() {
            var groupId = $(this).find('option:selected').val();
            var userId = $('#tickit_user_id').val();
            $.get(Routing.generate('permissions_list', { userId: userId, groupId: groupId }), function(data) {
                var hasPerms = false;
                $.each(data.permissions, function(i, perm) {
                    var $row = $('form > table tr[data-permission-id="' + i + '"]');
                    var $groupCheck = $row.find('#tickit_user_permissions_' + i + '_groupValue');
                    $groupCheck.prop('checked', perm.values.groupValue);
                    hasPerms = true;
                });

                if (!hasPerms) {
                    app.messaging.error('Permission Load Error', 'No permissions could be loaded for the selected group.');
                }
            });
        });
    },

    /**
     * Binds click events to the "override for user" checkboxes on the user permissions table.
     *
     * @return {void}
     */
    bindUserOverridePermissionToggle : function() {
        $('form').on('click', '.user-override', function() {
            var $row = $(this).parent().parent();
            var $input = $row.find('#tickit_user_permissions_' + $row.attr('data-permission-id') + '_userValue');
            if ($input.attr('disabled')) {
                $input.removeAttr('disabled');
            } else {
                $input.attr('disabled', 'disabled');
            }
        });
    }
};

$(function() {
    app.user.form.init();
});