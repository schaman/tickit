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
            var $table = $('#permissions-table');
            $.get(Routing.generate('permissions_list', { userId: userId, groupId: groupId }), function(data) {
                var hasPerms = false;

                // iterate once to check length (can't call .length on a native JS object)
                $.each(data.permissions, function() {
                    hasPerms = true;
                    return false;
                });

                if (!hasPerms) {
                    app.messaging.error('Permission Load Error', 'No permissions could be loaded for the selected group.');
                }

                if ($table.find('tbody tr').length > 1) {
                    updatePermissionRows(data.permissions);
                } else {
                    addPermissionRows(data.permissions);
                }

                /**
                 * Updates existing permission rows with new values
                 *
                 * @param {Object} permissions The new permissions
                 */
                function updatePermissionRows(permissions) {
                    $.each(permissions, function(i, perm) {
                        var $row = $table.find('tr[data-permission-id="' + i + '"]');
                        var $groupCheck = $row.find('#tickit_user_permissions_' + i + '_groupValue');
                        $groupCheck.prop('checked', perm.values.groupValue);
                    });
                }

                /**
                 * Adds new permission rows to an empty permissions table
                 *
                 * @param {Object} permissions The new permissions
                 */
                function addPermissionRows(permissions) {
                    $.each(permissions, function(i, perm) {
                        var $row = $($table.data('prototype').replace(/__name__/g, i));
                        $row.find('#tickit_user_permissions_' + i + '_groupValue').prop('checked', perm.values.group);
                        $row.find('#tickit_user_permissions_' + i + '_userValue').prop('checked', perm.values.user);
                        $row.find('#tickit_user_permissions_' + i + '_overridden').prop('checked', perm.overridden);
                        $row.find('td').first().prepend(perm.name);
                        $table.append($row);
                    });
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