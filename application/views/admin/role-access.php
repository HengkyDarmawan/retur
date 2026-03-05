<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Role Management: <?= $role['role']; ?></h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th rowspan="2" class="align-middle text-center" width="5%">#</th>
                            <th rowspan="2" class="align-middle">Menu / Submenu</th>
                            <th colspan="4" class="text-center">Standard Access (CRUD)</th>
                            <th colspan="3" class="text-center text-primary">Special Actions</th>
                        </tr>
                        <tr>
                            <th class="text-center">View</th>
                            <th class="text-center">Add</th>
                            <th class="text-center">Edit</th>
                            <th class="text-center">Del</th>
                            <th class="text-center bg-gray-100">Import</th>
                            <th class="text-center bg-gray-100">Export</th>
                            <th class="text-center bg-gray-100">Pass</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($menu as $m) : ?>
                            <tr class="bg-gray-200">
                                <td colspan="9"><strong>MENU: <?= strtoupper($m['menu']); ?></strong></td>
                            </tr>
                            <?php 
                            $subMenus = $this->db->get_where('user_sub_menu', ['menu_id' => $m['id']])->result_array();
                            $i = 1;
                            foreach ($subMenus as $sm) : 
                                $access = $this->db->get_where('user_access_control', [
                                    'role_id' => $role['id'],
                                    'submenu_id' => $sm['id']
                                ])->row_array();
                            ?>
                            <tr>
                                <td class="text-center"><?= $i++; ?></td>
                                <td><?= $sm['title']; ?></td>
                                <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_view', $access['can_view'] ?? 0); ?></td>
                                <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_add', $access['can_add'] ?? 0); ?></td>
                                <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_edit', $access['can_edit'] ?? 0); ?></td>
                                <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_delete', $access['can_delete'] ?? 0); ?></td>
                                <td class="text-center bg-gray-100"><?= render_checkbox($role['id'], $sm['id'], 'can_import', $access['can_import'] ?? 0); ?></td>
                                <td class="text-center bg-gray-100"><?= render_checkbox($role['id'], $sm['id'], 'can_export', $access['can_export'] ?? 0); ?></td>
                                <td class="text-center bg-gray-100"><?= render_checkbox($role['id'], $sm['id'], 'can_password', $access['can_password'] ?? 0); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
function render_checkbox($roleId, $submenuId, $type, $value) {
    $checked = ($value == 1) ? 'checked' : '';
    // ID harus unik, gabungkan type dan submenuId
    $elementId = $type . '_' . $submenuId;
    return "
        <div class='custom-control custom-switch'>
            <input type='checkbox' class='custom-control-input check-access' 
                id='{$elementId}' 
                data-role='{$roleId}' 
                data-submenu='{$submenuId}' 
                data-type='{$type}' 
                {$checked}>
            <label class='custom-control-label' for='{$elementId}'></label>
        </div>
    ";
}
?>