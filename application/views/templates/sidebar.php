<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('user'); ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Starter <sup>Pack</sup></div>
    </a>

    <hr class="sidebar-divider">

    <?php 
    $user_menus = get_sidebar_menu(); 
    foreach ($user_menus as $m) : 
    ?>
        <div class="sidebar-heading">
            <?= $m['menu']; ?>
        </div>

        <?php foreach ($m['sub_menu'] as $sm) : ?>
            <li class="nav-item <?= ($title == $sm['title']) ? 'active' : ''; ?>">
                <a class="nav-link pb-0" href="<?= base_url($sm['url']); ?>">
                    <i class="<?= $sm['icon']; ?> fa-fw"></i>
                    <span><?= $sm['title']; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
        
        <hr class="sidebar-divider d-none d-md-block mt-3">
    <?php endforeach; ?>

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->