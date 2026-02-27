<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Role : <?= $role['role']; ?></h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Menu / Submenu</th>
                                    <th class="text-center">View</th>
                                    <th class="text-center">Add</th>
                                    <th class="text-center">Edit</th>
                                    <th class="text-center">Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($menu as $m) : ?>
                                    <tr class="bg-gray-100">
                                        <td colspan="6"><strong>MENU: <?= strtoupper($m['menu']); ?></strong></td>
                                    </tr>
                                    <?php 
                                    // Ambil submenu untuk setiap menu
                                    $subMenus = $this->db->get_where('user_sub_menu', ['menu_id' => $m['id']])->result_array();
                                    $i = 1;
                                    foreach ($subMenus as $sm) : 
                                        // Ambil status akses saat ini dari database
                                        $access = $this->db->get_where('user_access_control', [
                                            'role_id' => $role['id'],
                                            'submenu_id' => $sm['id']
                                        ])->row_array();
                                    ?>
                                    <tr>
                                        <td><?= $i++; ?></td>
                                        <td><?= $sm['title']; ?></td>
                                        <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_view', $access['can_view'] ?? 0); ?></td>
                                        <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_add', $access['can_add'] ?? 0); ?></td>
                                        <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_edit', $access['can_edit'] ?? 0); ?></td>
                                        <td class="text-center"><?= render_checkbox($role['id'], $sm['id'], 'can_delete', $access['can_delete'] ?? 0); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Helper lokal untuk merender checkbox agar kode view lebih bersih
function render_checkbox($roleId, $submenuId, $type, $value) {
    $checked = ($value == 1) ? 'checked' : '';
    return "
        <div class='custom-control custom-switch'>
            <input type='checkbox' class='custom-control-input check-access' 
                id='{$type}_{$submenuId}' 
                data-role='{$roleId}' 
                data-submenu='{$submenuId}' 
                data-type='{$type}' 
                {$checked}>
            <label class='custom-control-label' for='{$type}_{$submenuId}'></label>
        </div>
    ";
}
?>