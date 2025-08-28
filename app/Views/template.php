<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Genefull | <?= $title; ?></title>
  <link rel="icon" href="<?= base_url(); ?>assets/img/iconku.png" type="image/png">
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/Bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/plugins/DataTables/datatables.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/reset.css?ts=<?= time() ?>">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/alert.css?ts=<?= time() ?>">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/style.css?ts=<?= time() ?>">

  <script src="<?= base_url(); ?>assets/plugins/Jquery/jquery.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/Bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/plugins/DataTables/datatables.min.js"></script>
  <script src="https://cdn.socket.io/4.5.0/socket.io.min.js"></script>
</head>

<body>
  <div class="page-full">
    <!-- HEADER -->
    <nav class="navbar">
      <div class="toggle-sidebar">
        <i class='bx bx-menu'></i>
      </div>
      <div class="navbar-brand">
        <div class="title-brand">
          <h1>Genefull</h1>
          <h2>Innovating Everyday Solutions</h2>
        </div>
        <img src="<?= base_url(); ?>assets/img/iconku.png" alt="logo">
      </div>
    </nav>
    <div class="separator-top"></div>
    <div class="page-wrapper">
      <aside class="sidebar">
        <div class="card-profile">
          <div class="img-wrapper">
            <img src="<?= base_url(); ?>assets/img/user.png" alt="user">
          </div>
          <div class="right-profile">
            <div class="desc-card-profile">
              <h1>Calon Gubernur</h1>
              <h2>@calongubernur</h2>
            </div>
            <div class="menu-card-profile">
              <a href=""><i class='bx bx-log-out'></i>Logout</a>
              <a href=""><i class='bx bx-user'></i>Profile</a>
            </div>
          </div>
        </div>
        <div class="sidebar-menu">
          <ul>
            <li><a href="<?= base_url(); ?>" class="<?= set_active(""); ?>"><i class='bx bx-home-alt'></i>Dashboard</a></li>
            <li><a href="<?= base_url('generate-quotes'); ?>" class="<?= set_active('generate-quotes'); ?>"><i class='bx  bx-credit-card-front'></i>Generate Quotes</a></li>
            <li><a href="<?= base_url('management-user'); ?>" class="<?= set_active('management-user'); ?>"><i class='bx bx-user'></i>Management User</a></li>
            <li class="has-dropdown">
              <a href="javascript:void(0)" class="<?= set_active('general', 'security'); ?>">
                <i class='bx bx-data'></i>Master
                <i class='bx bx-caret-right arrow'></i>
              </a>
              <ul class="dropdown">
                <li><a href="#"><i class='bx bxs-circle'></i>Account</a></li>
                <li><a href="#"><i class='bx bxs-circle'></i>Security</a></li>
              </ul>
            </li>
            <li class="has-dropdown">
              <a href="javascript:void(0)" class="<?= set_active('general', 'security'); ?>">
                <i class='bx  bx-slider-alt'></i>Setting
                <i class='bx bx-caret-right arrow'></i>
              </a>
              <ul class="dropdown">
                <li><a href="#"><i class='bx bxs-circle'></i>Profile</a></li>
                <li><a href="#"><i class='bx bxs-circle'></i>Security</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </aside>
      <div class="separator"></div>
      <!-- CONTENT -->
      <main class="content">
        <?= $this->renderSection("content"); ?>
      </main>
    </div>
    <!-- Loading dan popup -->
    <div id="popup" class="popup-hidden">
      <div class="popup-content">
        <img class="popup-icon" id="popup-icon" src="<?= base_url(); ?>" alt="icon" />
        <div class="popup-desc">
          <h2 id="popup-title">Title</h2>
          <p id="popup-message">Message</p>
        </div>
        <i class="fas fa-times" id="popup-close"></i>
      </div>
      <div class="popup-loading" id="popup-loading"></div>
    </div>
    <div id="confirm-popup" class="popup-hidden">
      <div class="popup-content">
        <img class="popup-icon" src="<?= base_url(); ?>assets/img/alert/info.png" alt="icon" />
        <div class="popup-desc">
          <h2 id="confirm-title">Konfirmasi</h2>
          <p id="confirm-message">Apakah Anda yakin ingin menghapus data ini?</p>
        </div>
        <div class="popup-buttons">
          <button id="confirm-yes" class="btn btn-danger">Yes</button>
          <button id="confirm-no" class="btn btn-secondary">No</button>
        </div>
        <i class="fas fa-times" id="confirm-close"></i>
      </div>
    </div>

  </div>
  <script src="<?= base_url(); ?>assets/js/alert.js"></script>
  <script>
    window.addEventListener('load', function() {
      document.querySelector('.content').classList.add('show');
    });

    document.querySelectorAll('.sidebar .has-dropdown > a').forEach(item => {
      item.addEventListener('click', function() {
        this.parentElement.classList.toggle('open');
      });
    });
  </script>
</body>

</html>