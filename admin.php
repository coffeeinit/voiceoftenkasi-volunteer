<?php
session_start();
// Simple admin authentication
$adminPassword = 'VOT@2024';
// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit;
}
if (isset($_POST['admin_login'])) {
    $pass = $_POST['admin_password'] ?? '';
    if ($pass === $adminPassword) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        $loginError = 'Incorrect password.';
    }
}
if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    
    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Admin Login - Volunteer List</title><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></head><body class="hold-transition login-page"><div class="login-box"><div class="login-logo mb-4"><b>Voice of Tenkasi</b><br><small>Volunteer Admin</small></div><div class="card"><div class="card-body login-card-body"><p class="login-box-msg">Sign in to access the admin panel</p>';
    if (!empty($loginError)) echo '<div class="alert alert-danger">'.htmlspecialchars($loginError).'</div>';
    echo '<form method="POST" autocomplete="off"><div class="input-group mb-3"><input type="password" name="admin_password" class="form-control" placeholder="Admin password" required><div class="input-group-append"><div class="input-group-text"><span class="fas fa-lock"></span></div></div></div><div class="row"><div class="col-12"><button type="submit" name="admin_login" class="btn btn-primary btn-block">Login</button></div></div></form></div></div></div><script src="https://code.jquery.com/jquery-3.6.0.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script><script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script></body></html>';
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Volunteer Admin - Voice of Tenkasi Foundation</title>
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
  
  <!-- Premium Admin Styles -->
  <style>
    * { 
        margin: 0; 
        padding: 0; 
        box-sizing: border-box; 
    }
    
    body { 
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        color: #333;
    }
    
    /* Header */
    .admin-header {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 20px 0;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }
    
    .header-content {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .logo-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .logo-section img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .logo-text h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #2563eb;
        margin: 0;
    }
    
    .logo-text p {
        font-size: 0.9rem;
        color: #666;
        margin: 0;
    }
    
    .header-actions {
        display: flex;
        gap: 15px;
        align-items: center;
    }
    
    .btn-logout {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 14px rgba(220, 38, 38, 0.25);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-logout:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(220, 38, 38, 0.4);
        color: white;
        text-decoration: none;
    }
    
    /* Main Container */
    .admin-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
    }
    
    /* Stats Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 25px;
        margin-bottom: 40px;
    }
    
    .stat-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #2563eb, #7c3aed, #dc2626, #059669);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-icon.blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
    .stat-icon.green { background: linear-gradient(135deg, #059669, #047857); }
    .stat-icon.orange { background: linear-gradient(135deg, #ea580c, #c2410c); }
    .stat-icon.purple { background: linear-gradient(135deg, #7c3aed, #6d28d9); }
    
    .stat-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: #1f2937;
        margin: 0;
    }
    
    .stat-label {
        font-size: 1rem;
        color: #6b7280;
        font-weight: 600;
        margin: 0;
    }
    
    /* Content Cards */
    .content-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        margin-bottom: 30px;
        overflow: hidden;
    }
    
    .card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 25px 30px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .card-title i {
        color: #2563eb;
        font-size: 1.3rem;
    }
    
    .card-actions {
        display: flex;
        gap: 10px;
    }
    
    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        color: white;
        box-shadow: 0 4px 14px rgba(5, 150, 105, 0.25);
    }
    
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        color: white;
        text-decoration: none;
    }
    
    /* Table Styling */
    .table-container {
        padding: 30px;
    }
    
    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }
    
    .table th {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        color: #374151;
        font-weight: 700;
        padding: 15px 12px;
        text-align: left;
        border-bottom: 2px solid #e5e7eb;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .table td {
        padding: 15px 12px;
        border-bottom: 1px solid #f3f4f6;
        vertical-align: middle;
    }
    
    .table tbody tr {
        transition: all 0.3s ease;
    }
    
    .table tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }
    
    .volunteer-photo {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #e5e7eb;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .badge-status {
        font-size: 0.8rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .badge-active {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    
    .badge-pending {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
        border: 1px solid #fde68a;
    }
    
    .badge-inactive {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            gap: 20px;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }
        
        .card-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .table-container {
            padding: 15px;
            overflow-x: auto;
        }
        
        .table {
            min-width: 600px;
        }
    }
    
    /* Loading Animation */
    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid #2563eb;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Success/Error Messages */
    .alert {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        color: #047857;
        border: 1px solid #a7f3d0;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    /* Form Controls */
    .form-control {
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 16px;
        transition: all 0.3s ease;
        background: #fafbfc;
    }
    
    .form-control:focus {
        outline: none;
        border-color: #2563eb;
        background: white;
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    
    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        text-decoration: none;
    }
    
    /* Additional styles for existing elements */
    .tag-chip {
        display: inline-flex;
        align-items: center;
        background: #6c757d;
        color: #fff;
        border-radius: 14px;
        padding: 4px 10px;
        font-size: 12px;
        margin-right: 4px;
        margin-bottom: 4px;
    }
    
    .tag-chip .remove-tag {
        margin-left: 6px;
        cursor: pointer;
        font-weight: bold;
        color: #fff;
        opacity: 0.7;
    }
    
    .tag-chip .remove-tag:hover { 
        opacity: 1; 
    }
    
    .profile-image-upload {
        border: 2px dashed #dee2e6;
        border-radius: 5px;
        padding: 15px;
        text-align: center;
        cursor: pointer;
        margin-bottom: 15px;
    }
    
    .preview-image {
        max-width: 150px;
        max-height: 150px;
        border-radius: 5px;
        margin-top: 10px;
    }
    
    .social-media-link {
        color: #6c757d;
        font-size: 1.2rem;
        margin-right: 10px;
    }
    
    .social-media-link:hover {
        color: #007bff;
    }
    
    .volunteer-details-table td {
        border-top: none;
        padding: 5px 10px;
    }
    
    .volunteer-details-table td:first-child {
        font-weight: 600;
        width: 140px;
        color: #495057;
    }
    
    /* Custom checkbox and radio alignment */
    .form-group .form-check {
        padding-left: 0;
    }
    
    .form-group .form-check-inline {
        margin-right: 15px;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="admin.php" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="registration.php" class="nav-link">Add Volunteer</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge" id="notificationCount">0</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header" id="notificationHeader">No Notifications</span>
          <div class="dropdown-divider"></div>
          <div id="notificationList">
            <!-- Notifications will be loaded here -->
          </div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>
      <li class="nav-item">
        <form method="get" action="admin.php">
          <button type="submit" name="logout" value="1" class="btn btn-link nav-link">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="admin.php" class="brand-link text-center">
      <span class="brand-text font-weight-light">Voice of Tenkasi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="admin.php" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="registration.php" class="nav-link">
              <i class="nav-icon fas fa-user-plus"></i>
              <p>Add New Volunteer</p>
            </a>
          </li>
          <li class="nav-item">
  <a href="javascript:void(0)" class="nav-link" onclick="exportAllCSV()">
    <i class="nav-icon fas fa-download"></i>
    <p>Export Data</p>
  </a>
</li>

        
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Volunteer Management</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="admin.php">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3 id="totalVolunteers">0</h3>
                <p>Total Volunteers</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-people"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3 id="activeVolunteers">0</h3>
                <p>Active Volunteers</p>
              </div>
              <div class="icon">
                <i class="ion ion-checkmark-circled"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3 id="newThisMonth">0</h3>
                <p>New This Month</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3 id="bloodDonors">0</h3>
                <p>Blood Donors</p>
              </div>
              <div class="icon">
                <i class="ion ion-waterdrop"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Volunteer Distribution
                </h3>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <div class="chart-container">
                    <canvas id="volunteerChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->

          <!-- Right col -->
          <section class="col-lg-5 connectedSortable">
            
            <!-- Volunteer Map -->
            <div class="card bg-gradient-primary">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-map-marker-alt mr-1"></i>
                  Volunteers by Location
                </h3>
              </div>
              <div class="card-body">
                <div id="volunteer-map" style="height: 250px; width: 100%;">
                  <div class="text-center p-4 text-white">
                    <i class="fas fa-map-marked-alt fa-3x mb-2"></i>
                    <p>Map visualization of volunteer locations</p>
                    <small>Interactive map coming soon</small>
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- Blood Group Distribution -->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Blood Group Distribution</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="progress-group">
                  O+ 
                  <span class="float-right"><b id="op-count">0</b>/<span id="total-count">0</span></span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-primary" id="op-progress" style="width: 0%"></div>
                  </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                  A+
                  <span class="float-right"><b id="ap-count">0</b>/<span id="total-count2">0</span></span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-danger" id="ap-progress" style="width: 0%"></div>
                  </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                  B+
                  <span class="float-right"><b id="bp-count">0</b>/<span id="total-count3">0</span></span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-success" id="bp-progress" style="width: 0%"></div>
                  </div>
                </div>
                <!-- /.progress-group -->
                <div class="progress-group">
                  AB+
                  <span class="float-right"><b id="abp-count">0</b>/<span id="total-count4">0</span></span>
                  <div class="progress progress-sm">
                    <div class="progress-bar bg-warning" id="abp-progress" style="width: 0%"></div>
                  </div>
                </div>
                <!-- /.progress-group -->
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- /.Right col -->
        </div>
        <!-- /.row (main row) -->

        <!-- Volunteer Table Row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Volunteer List</h3>
                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search" id="globalSearch">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default">
                        <i class="fas fa-search"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-3">
                    <select class="form-control" id="filterStatus">
                      <option value="">All Status</option>
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" id="filterBloodGroup">
                      <option value="">All Blood Groups</option>
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
                      <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                    </select>
                  </div>
                  <div class="col-md-3">
                    <select class="form-control" id="filterLocation">
                      <option value="">All Locations</option>
                      <!-- Locations will be populated dynamically -->
                    </select>
                  </div>
                  <div class="col-md-3">
                    <button class="btn btn-default btn-block" onclick="loadAllVolunteers()">
                      <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                  </div>
                </div>
                
                <table id="volunteersTable" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Photo</th>
                      <th>Name</th>
                      <th>Contact</th>
                      <th>Blood Group</th>
                      <th>Location</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody id="volunteersBody">
                    <!-- Volunteer data will be loaded here -->
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="https://voiceoftenkasi.org">Voice of Tenkasi Foundation</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 2.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- Volunteer View Modal -->
<div class="modal fade" id="volunteerModal" tabindex="-1" role="dialog" aria-labelledby="volunteerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="volunteerModalLabel">Volunteer Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modalContent">
        <!-- Content will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="editVolunteerFromViewModal()">Edit</button>
      </div>
    </div>
  </div>
</div>

<!-- Volunteer Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Volunteer</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editVolunteerForm" autocomplete="off" enctype="multipart/form-data">
          <input type="hidden" name="id" id="edit_id">
          <div class="row">
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Personal Information</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="edit_first_name">First Name *</label>
                    <input type="text" class="form-control" name="first_name" id="edit_first_name" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_last_name">Last Name *</label>
                    <input type="text" class="form-control" name="last_name" id="edit_last_name" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_dob">Date of Birth (DD/MM/YYYY) *</label>
                    <input type="text" class="form-control" name="dob" id="edit_dob" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_email">Email</label>
                    <input type="email" class="form-control" name="email" id="edit_email">
                  </div>
                  <div class="form-group">
                    <label for="edit_blood_group">Blood Group *</label>
                    <select class="form-control" name="blood_group" id="edit_blood_group" required>
                      <option value="">Select Blood Group</option>
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
                      <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="edit_contact_no">Mobile *</label>
                    <input type="text" class="form-control" name="contact_no" id="edit_contact_no" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_whatsapp_no">WhatsApp *</label>
                    <input type="text" class="form-control" name="whatsapp_no" id="edit_whatsapp_no" required>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Additional Information</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="edit_qualification">Qualification *</label>
                    <input type="text" class="form-control" name="qualification" id="edit_qualification" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_occupation">Occupation *</label>
                    <input type="text" class="form-control" name="occupation" id="edit_occupation" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_address1">Address 1 *</label>
                    <input type="text" class="form-control" name="address1" id="edit_address1" required>
                  </div>
                  <div class="form-group">
                    <label for="edit_address2">Address 2</label>
                    <input type="text" class="form-control" name="address2" id="edit_address2">
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="edit_city">City *</label>
                        <input type="text" class="form-control" name="city" id="edit_city" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="edit_state">State *</label>
                        <input type="text" class="form-control" name="state" id="edit_state" required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="edit_zip">ZIP *</label>
                        <input type="text" class="form-control" name="zip" id="edit_zip" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="edit_travel_km">Travel KM</label>
                    <input type="number" class="form-control" name="travel_km" id="edit_travel_km">
                  </div>
                  <div class="form-group">
                    <label>Preferred Locations *</label>
                    <div id="edit_locationsTagInput" class="tag-input-container form-control" style="min-height: 38px; display: flex; flex-wrap: wrap; align-items: center;">
                      <input type="text" id="edit_preferredLocationsInput" class="form-control" style="border: none; box-shadow: none; width: auto; min-width: 80px; flex: 1 0 80px;" placeholder="Add location...">
                    </div>
                    <input type="hidden" name="preferred_locations" id="edit_preferredLocationsHidden" required>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Availability & Interests</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Days Available</label>
                    <div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Monday">
                        <label class="form-check-label">Monday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Tuesday">
                        <label class="form-check-label">Tuesday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Wednesday">
                        <label class="form-check-label">Wednesday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Thursday">
                        <label class="form-check-label">Thursday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Friday">
                        <label class="form-check-label">Friday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Saturday">
                        <label class="form-check-label">Saturday</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_days_cb" type="checkbox" name="days[]" value="Sunday">
                        <label class="form-check-label">Sunday</label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label>Areas of Interest</label>
                    <div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Education">
                        <label class="form-check-label">Education</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Healthcare">
                        <label class="form-check-label">Healthcare</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Environment">
                        <label class="form-check-label">Environment</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Community Development">
                        <label class="form-check-label">Community Development</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Elderly Care">
                        <label class="form-check-label">Elderly Care</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Children Welfare">
                        <label class="form-check-label">Children Welfare</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Disaster Relief">
                        <label class="form-check-label">Disaster Relief</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_interests_cb" type="checkbox" name="interests[]" value="Awareness Programs">
                        <label class="form-check-label">Awareness Programs</label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="edit_motto">Motivation Statement</label>
                    <textarea class="form-control" name="motto" id="edit_motto" rows="3"></textarea>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">Social Media & Additional Details</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="edit_instagram">Instagram</label>
                    <input type="text" class="form-control" name="instagram" id="edit_instagram">
                  </div>
                  <div class="form-group">
                    <label for="edit_facebook">Facebook</label>
                    <input type="text" class="form-control" name="facebook" id="edit_facebook">
                  </div>
                  <div class="form-group">
                    <label for="edit_twitter">Twitter</label>
                    <input type="text" class="form-control" name="twitter" id="edit_twitter">
                  </div>
                  <div class="form-group">
                    <label for="edit_linkedin">LinkedIn</label>
                    <input type="text" class="form-control" name="linkedin" id="edit_linkedin">
                  </div>
                  
                  <div class="form-group">
                    <label>Willing to work in Social Media?</label>
                    <div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_social_repost_rb" type="radio" name="social_repost" value="Yes">
                        <label class="form-check-label">Yes</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_social_repost_rb" type="radio" name="social_repost" value="Maybe">
                        <label class="form-check-label">Maybe</label>
                      </div>
                      <div class="form-check form-check-inline">
                        <input class="form-check-input edit_social_repost_rb" type="radio" name="social_repost" value="No">
                        <label class="form-check-label">No</label>
                      </div>
                    </div>
                  </div>
                  
                  <div class="form-group">
                    <label for="edit_donate">Willing to Donate Blood? *</label>
                    <select class="form-control" name="donate" id="edit_donate" required>
                      <option value="">Select Option</option>
                      <option value="Yes">Yes</option>
                      <option value="No">No</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="edit_status">Status</label>
                    <select class="form-control" name="status" id="edit_status">
                      <option value="active">Active</option>
                      <option value="inactive">Inactive</option>
                    </select>
                  </div>
                  
                  <div class="form-group">
                    <label for="edit_volunteer_photo">Profile Photo</label>
                    <div class="profile-image-upload" onclick="document.getElementById('edit_volunteer_photo').click()">
                      <i class="fas fa-cloud-upload-alt fa-2x"></i>
                      <p>Click to upload or drag and drop</p>
                      <img id="edit_photoPreview" class="preview-image" style="display: none;">
                    </div>
                    <input type="file" id="edit_volunteer_photo" name="volunteer_photo" accept="image/*" style="display: none;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" onclick="document.getElementById('editVolunteerForm').dispatchEvent(new Event('submit'))">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Moment -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<!-- Date Range Picker -->
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
// Initialize DataTable
$(document).ready(function() {
    $('#volunteersTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
    
    // Initialize Select2
    $('.select2').select2();
    
    // Load initial data
    loadAllVolunteers();
    loadDashboardStats();
    loadRecentActivities();
});

 function exportAllCSV() {
            window.open('form-handler.php?action=export_all_csv', '_blank');
        }
        
// Global variables
let currentVolunteers = [];
let volunteerChart = null;


// Load all volunteers
function loadAllVolunteers() {
    $('#volunteersTable').DataTable().destroy();
    $('#volunteersBody').html('<tr><td colspan="7" class="text-center">Loading volunteers...</td></tr>');
    
    fetch('form-handler.php?action=get_all_volunteers')
        .then(response => response.json())
        .then(data => {
            currentVolunteers = data;
            displayVolunteers(data);
            updateDashboardStats(data);
            updateBloodGroupStats(data);
            initVolunteerChart(data);
            populateLocationFilter(data);
        })
        .catch(error => {
            showToast('Error loading volunteers', false);
        });
}

// Display volunteers in the table
function displayVolunteers(volunteers) {
    const tbody = $('#volunteersBody');
    tbody.empty();
    
    if (!volunteers || !Array.isArray(volunteers) || volunteers.length === 0) {
        tbody.html('<tr><td colspan="7" class="text-center">No volunteers found</td></tr>');
        return;
    }
    
    volunteers.forEach(volunteer => {
        const statusClass = (volunteer.status || 'active') === 'active' ? 'badge-success' : 'badge-secondary';
        const statusText = (volunteer.status || 'active') === 'active' ? 'Active' : 'Inactive';
        
   const row = `
<tr>
  <!-- Photo + Initials -->
<td class="text-center align-middle">
  ${volunteer.photo ? `
    <img 
      src="volunteer_photos/${volunteer.photo}" 
      class="volunteer-photo rounded-circle mb-2" 
      alt="Photo" 
      style="width: 80px; height: 80px; object-fit: cover;" 
      onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
    >
  ` : ''}
  
  
</td>


  <!-- Name -->
  <td class="align-middle">
    <strong>${volunteer.first_name} ${volunteer.last_name}</strong>
  </td>

  <!-- Contact -->
  <td class="align-middle">
    ${volunteer.contact_no}<br>
    <small class="text-muted">${volunteer.whatsapp_no}</small>
  </td>

  <!-- Blood Group -->
  <td class="align-middle text-center">
    <span class="badge badge-danger">${volunteer.blood_group}</span>
  </td>

  <!-- Location -->
  <td class="align-middle">
    ${volunteer.city}, ${volunteer.state}
  </td>

  <!-- Status -->
  <td class="align-middle text-center">
    <span class="badge ${statusClass} badge-status">${statusText}</span>
  </td>

  <!-- Actions -->
  <td class="align-middle text-center">
    <div class="d-flex flex-wrap gap-1 justify-content-center">
      <button class="btn btn-info btn-sm" title="View" onclick="viewVolunteer(${volunteer.id})">
        <i class="fas fa-eye"></i>
      </button>

      <button class="btn btn-primary btn-sm" title="Edit" onclick="editVolunteer(${volunteer.id})">
        <i class="fas fa-edit"></i>
      </button>

      ${(volunteer.status || 'active') === 'active' ?
        `<button class="btn btn-warning btn-sm" title="Deactivate" onclick="deactivateVolunteer(${volunteer.id})">
          <i class="fas fa-ban"></i>
        </button>` :
        `<button class="btn btn-success btn-sm" title="Activate" onclick="activateVolunteer(${volunteer.id})">
          <i class="fas fa-check"></i>
        </button>`}

      <button class="btn btn-sm" style="background:#3b5bdb; color:#fff;" title="Copy Profile Link" 
              onclick='copyProfileLinkModal("${volunteer.registration_id}")'>
        <i class="fas fa-link"></i>
      </button>

      <a href="form-handler.php?action=download_pdf_volunteer&ref=${encodeURIComponent(volunteer.registration_id)}" 
         target="_blank" 
         class="btn btn-sm" 
         style="background:#27ae60; color:#fff; text-decoration:none;" 
         title="Download PDF">
        <i class="fas fa-download"></i>
      </a>
    </div>
  </td>
</tr>
`;

        tbody.append(row);
    });
    
    // Reinitialize DataTable
    $('#volunteersTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "dom": 'Bfrtip',
        "buttons": [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
}

 function copyProfileLinkModal(regId) {
            const url = window.location.origin + '/form-handler.php?profile=' + encodeURIComponent(regId);
            navigator.clipboard.writeText(url).then(function() {
                showToast('Profile link copied!');
            }, function() {
                showToast('Failed to copy link.', false);
            });
        }
// Load dashboard statistics
function loadDashboardStats() {
    // This would typically come from an API endpoint
    $('#totalVolunteers').text('0');
    $('#activeVolunteers').text('0');
    $('#newThisMonth').text('0');
    $('#bloodDonors').text('0');
}

// Update dashboard statistics based on volunteer data
function updateDashboardStats(volunteers) {
    const total = volunteers.length;
    const active = volunteers.filter(v => (v.status || 'active') === 'active').length;
    
    // Calculate new this month (assuming created_at field exists)
    const thisMonth = new Date().getMonth();
    const thisYear = new Date().getFullYear();
    const newThisMonth = volunteers.filter(v => {
        if (!v.created_at) return false;
        const createdDate = new Date(v.created_at);
        return createdDate.getMonth() === thisMonth && createdDate.getFullYear() === thisYear;
    }).length;
    
    // Count blood donors
    const bloodDonors = volunteers.filter(v => v.donate === 'Yes').length;
    
    $('#totalVolunteers').text(total);
    $('#activeVolunteers').text(active);
    $('#newThisMonth').text(newThisMonth);
    $('#bloodDonors').text(bloodDonors);
}

// Update blood group statistics
function updateBloodGroupStats(volunteers) {
    const bloodGroups = {
        'O+': 0, 'A+': 0, 'B+': 0, 'AB+': 0,
        'O-': 0, 'A-': 0, 'B-': 0, 'AB-': 0
    };
    
    volunteers.forEach(v => {
        if (v.blood_group && bloodGroups.hasOwnProperty(v.blood_group)) {
            bloodGroups[v.blood_group]++;
        }
    });
    
    const total = volunteers.length;
    
    // Update counts
    $('#op-count').text(bloodGroups['O+']);
    $('#ap-count').text(bloodGroups['A+']);
    $('#bp-count').text(bloodGroups['B+']);
    $('#abp-count').text(bloodGroups['AB+']);
    
    // Update progress bars
    $('#op-progress').css('width', total ? (bloodGroups['O+'] / total * 100) + '%' : '0%');
    $('#ap-progress').css('width', total ? (bloodGroups['A+'] / total * 100) + '%' : '0%');
    $('#bp-progress').css('width', total ? (bloodGroups['B+'] / total * 100) + '%' : '0%');
    $('#abp-progress').css('width', total ? (bloodGroups['AB+'] / total * 100) + '%' : '0%');
}

// Initialize volunteer distribution chart
function initVolunteerChart(volunteers) {
    const ctx = document.getElementById('volunteerChart').getContext('2d');
    
    // Count volunteers by city
    const cities = {};
    volunteers.forEach(v => {
        if (v.city) {
            cities[v.city] = (cities[v.city] || 0) + 1;
        }
    });
    
    // Sort and get top 5 cities
    const sortedCities = Object.entries(cities)
        .sort((a, b) => b[1] - a[1])
        .slice(0, 5);
    
    const labels = sortedCities.map(item => item[0]);
    const data = sortedCities.map(item => item[1]);
    
    if (volunteerChart) {
        volunteerChart.destroy();
    }
    
    volunteerChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: [
                    '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                    '#e83e8c', '#fd7e14', '#20c997', '#6610f2', '#6c757d'
                ]
            }]
        },
        options: {
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
}



// Populate location filter dropdown
function populateLocationFilter(volunteers) {
    const locations = new Set();
    volunteers.forEach(v => {
        if (v.city) locations.add(v.city);
    });
    
    const filter = $('#filterLocation');
    filter.empty();
    filter.append('<option value="">All Locations</option>');
    
    locations.forEach(location => {
        filter.append(`<option value="${location}">${location}</option>`);
    });
}

// View volunteer details
function viewVolunteer(id) {
    const volunteer = currentVolunteers.find(v => v.id == id);
    if (!volunteer) return;
    
    const statusClass = (volunteer.status || 'active') === 'active' ? 'badge-success' : 'badge-secondary';
    const statusText = (volunteer.status || 'active') === 'active' ? 'Active' : 'Inactive';
    
    $('#modalContent').html(`
        <div class="row">
            <div class="col-md-4 text-center">
                ${volunteer.photo ? `<img src="volunteer_photos/${volunteer.photo}" class="img-fluid rounded-circle mb-3" style="max-width: 200px;">` : `<div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white mb-3"      style="width: 200px; height: 200px; font-size: 3rem;">     ${volunteer.first_name.charAt(0)}${volunteer.last_name.charAt(0)}    </div>`
                }
                <h3>${volunteer.first_name} ${volunteer.last_name}</h3>
                <p class="text-muted">${volunteer.registration_id}</p>
                <span class="badge ${statusClass} badge-status">${statusText}</span>
            </div>
            <div class="col-md-8">
                <table class="table volunteer-details-table">
                    <tr><td>Email:</td><td>${volunteer.email || 'N/A'}</td></tr>
                    <tr><td>Mobile:</td><td>${volunteer.contact_no}</td></tr>
                    <tr><td>WhatsApp:</td><td>${volunteer.whatsapp_no}</td></tr>
                    <tr><td>Blood Group:</td><td><span class="badge badge-danger">${volunteer.blood_group}</span></td></tr>
                    <tr><td>Date of Birth:</td><td>${volunteer.dob || 'N/A'} ${volunteer.dob ? '(' + calculateAge(volunteer.dob) + ' yrs)' : ''}</td></tr>
                    <tr><td>Address:</td><td>${volunteer.address1}${volunteer.address2 ? ', ' + volunteer.address2 : ''}, ${volunteer.city}, ${volunteer.state} - ${volunteer.zip}</td></tr>
                    <tr><td>Qualification:</td><td>${volunteer.qualification}</td></tr>
                    <tr><td>Occupation:</td><td>${volunteer.occupation}</td></tr>
                    <tr><td>Travel KM:</td><td>${volunteer.travel_km || 'Not specified'}</td></tr>
                    <tr><td>Preferred Locations:</td><td>${volunteer.preferred_locations || ''}</td></tr>
                    <tr><td>Days Available:</td><td>${volunteer.days || ''}</td></tr>
                    <tr><td>Interests:</td><td>${volunteer.interests || ''}</td></tr>
                    <tr><td>Motivation:</td><td>${volunteer.motto || ''}</td></tr>
                    <tr><td>Donate Blood:</td><td>${volunteer.donate || ''}</td></tr>
                    <tr><td>Social Media Work:</td><td>${volunteer.social_repost || ''}</td></tr>
                    <tr><td>Registered:</td><td>${volunteer.created_at || ''}</td></tr>
                </table>
                
                <div class="social-media-links mt-3">
                    ${volunteer.instagram ? `<a href="${volunteer.instagram}" target="_blank" class="social-media-link"><i class="fab fa-instagram"></i></a>` : ''}
                    ${volunteer.facebook ? `<a href="${volunteer.facebook}" target="_blank" class="social-media-link"><i class="fab fa-facebook"></i></a>` : ''}
                    ${volunteer.twitter ? `<a href="${volunteer.twitter}" target="_blank" class="social-media-link"><i class="fab fa-twitter"></i></a>` : ''}
                    ${volunteer.linkedin ? `<a href="${volunteer.linkedin}" target="_blank" class="social-media-link"><i class="fab fa-linkedin"></i></a>` : ''}
                </div>
            </div>
        </div>
    `);
    
    $('#volunteerModal').modal('show');
}

// Edit volunteer
function editVolunteer(id) {
    const volunteer = currentVolunteers.find(v => v.id == id);
    if (!volunteer) return;
    
    // Fill form fields
    $('#edit_id').val(volunteer.id);
    $('#edit_first_name').val(volunteer.first_name || '');
    $('#edit_last_name').val(volunteer.last_name || '');
    $('#edit_email').val(volunteer.email || '');
    $('#edit_blood_group').val(volunteer.blood_group || '');
    $('#edit_contact_no').val(volunteer.contact_no || '');
    $('#edit_whatsapp_no').val(volunteer.whatsapp_no || '');
    $('#edit_qualification').val(volunteer.qualification || '');
    $('#edit_occupation').val(volunteer.occupation || '');
    $('#edit_address1').val(volunteer.address1 || '');
    $('#edit_address2').val(volunteer.address2 || '');
    $('#edit_city').val(volunteer.city || '');
    $('#edit_state').val(volunteer.state || '');
    $('#edit_zip').val(volunteer.zip || '');
    $('#edit_travel_km').val(volunteer.travel_km || '');
    $('#edit_dob').val(volunteer.dob || '');
    
    // Preferred locations (multi-select)
    editTags = (volunteer.preferred_locations||'').split(',').map(s=>s.trim()).filter(Boolean);
    renderEditTags();
    
    // Days (checkboxes)
    const daysArr = (volunteer.days||'').split(',').map(s=>s.trim());
    $('.edit_days_cb').each(function() {
        $(this).prop('checked', daysArr.includes($(this).val()));
    });
    
    // Interests (checkboxes)
    const intArr = (volunteer.interests||'').split(',').map(s=>s.trim());
    $('.edit_interests_cb').each(function() {
        $(this).prop('checked', intArr.includes($(this).val()));
    });
    
    $('#edit_motto').val(volunteer.motto || '');
    $('#edit_instagram').val(volunteer.instagram || '');
    $('#edit_facebook').val(volunteer.facebook || '');
    $('#edit_twitter').val(volunteer.twitter || '');
    $('#edit_linkedin').val(volunteer.linkedin || '');
    
    // Social repost (radio)
    $(`.edit_social_repost_rb[value="${volunteer.social_repost || ''}"]`).prop('checked', true);
    
    $('#edit_donate').val(volunteer.donate || '');
    $('#edit_status').val(volunteer.status || 'active');
    
    // Photo preview
    const photoPreview = $('#edit_photoPreview');
    if (volunteer.photo) {
        photoPreview.attr('src', 'volunteer_photos/' + volunteer.photo).show();
    } else {
        photoPreview.hide();
    }
    
    $('#editModal').modal('show');
}

// Handle form submission for editing volunteer
$('#editVolunteerForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('form-handler.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#editModal').modal('hide');
            loadAllVolunteers();
            showToast('Volunteer updated successfully!');
        } else {
            showToast(data.message || 'Error updating volunteer', false);
        }
    })
    .catch(() => {
        showToast('Error updating volunteer', false);
    });
});

// Delete volunteer
function deleteVolunteer(id) {
    if (!confirm('Are you sure you want to delete this volunteer? This action cannot be undone.')) return;
    
    fetch(`form-handler.php?action=delete&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAllVolunteers();
                showToast('Volunteer deleted!');
            } else {
                showToast('Error deleting volunteer', false);
            }
        })
        .catch(error => {
            showToast('Error deleting volunteer', false);
        });
}

// Deactivate volunteer
function deactivateVolunteer(id) {
    if (!confirm('Are you sure you want to deactivate this volunteer?')) return;
    
    fetch(`form-handler.php?action=deactivate&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAllVolunteers();
                showToast('Volunteer deactivated!');
            } else {
                showToast('Error deactivating volunteer', false);
            }
        })
        .catch(error => {
            showToast('Error deactivating volunteer', false);
        });
}

// Activate volunteer
function activateVolunteer(id) {
    if (!confirm('Are you sure you want to activate this volunteer?')) return;
    
    fetch(`form-handler.php?action=activate&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadAllVolunteers();
                showToast('Volunteer activated!');
            } else {
                showToast('Error activating volunteer', false);
            }
        })
        .catch(error => {
            showToast('Error activating volunteer', false);
        });
}

// Edit volunteer from view modal
function editVolunteerFromViewModal() {
    $('#volunteerModal').modal('hide');
    setTimeout(() => {
        const volunteerId = $('#edit_id').val();
        if (volunteerId) {
            editVolunteer(volunteerId);
        }
    }, 500);
}

// Calculate age from date of birth
function calculateAge(dobStr) {
    if (!dobStr) return '';
    const parts = dobStr.split('/');
    if (parts.length !== 3) return '';
    
    const day = parseInt(parts[0], 10);
    const month = parseInt(parts[1], 10) - 1;
    const year = parseInt(parts[2], 10);
    
    if (isNaN(day) || isNaN(month) || isNaN(year)) return '';
    
    const dob = new Date(year, month, day);
    if (isNaN(dob.getTime())) return '';
    
    const today = new Date();
    let age = today.getFullYear() - dob.getFullYear();
    const m = today.getMonth() - dob.getMonth();
    
    if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
        age--;
    }
    
    return age;
}

// Show toast notification
function showToast(msg, success = true) {
    // Create toast element if it doesn't exist
    if (!$('#toast').length) {
        $('body').append('<div id="toast" class="toast" style="position: fixed; top: 20px; right: 20px; z-index: 9999;" data-autohide="true" data-delay="3000"><div class="toast-header"><strong class="mr-auto">Notification</strong><button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button></div><div class="toast-body"></div></div>');
    }
    
    const toast = $('#toast');
    toast.find('.toast-body').text(msg);
    toast.removeClass('bg-success bg-danger');
    toast.addClass(success ? 'bg-success' : 'bg-danger');
    toast.toast('show');
}

// Tag management for preferred locations
let editTags = [];

function renderEditTags() {
    $('#edit_locationsTagInput').find('.tag-chip').remove();
    
    editTags.forEach((tag, idx) => {
        const chip = $('<span class="tag-chip">' + tag + '<span class="remove-tag" data-index="' + idx + '">×</span></span>');
        chip.find('.remove-tag').on('click', function() {
            const index = $(this).data('index');
            editTags.splice(index, 1);
            renderEditTags();
        });
        $('#edit_locationsTagInput').prepend(chip);
    });
    
    $('#edit_preferredLocationsHidden').val(editTags.join(', '));
}

$('#edit_preferredLocationsInput').on('keydown', function(e) {
    if (e.key === 'Enter' || e.key === ',') {
        e.preventDefault();
        let value = $(this).val().trim().replace(/,$/, '');
        if (value && !editTags.includes(value)) {
            editTags.push(value);
            renderEditTags();
        }
        $(this).val('');
        return false;
    }
});

$('#edit_preferredLocationsInput').on('blur', function() {
    let value = $(this).val().trim().replace(/,$/, '');
    if (value && !editTags.includes(value)) {
        editTags.push(value);
        renderEditTags();
    }
    $(this).val('');
});

// Photo preview for edit modal
$('#edit_volunteer_photo').on('change', function(e) {
    const file = e.target.files[0];
    const photoPreview = $('#edit_photoPreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            photoPreview.attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    }
});

// Apply filters
$('#filterStatus, #filterBloodGroup, #filterLocation').on('change', function() {
    const status = $('#filterStatus').val();
    const bloodGroup = $('#filterBloodGroup').val();
    const location = $('#filterLocation').val();
    
    let filtered = currentVolunteers;
    
    if (status) {
        filtered = filtered.filter(v => (v.status || 'active') === status);
    }
    
    if (bloodGroup) {
        filtered = filtered.filter(v => v.blood_group === bloodGroup);
    }
    
    if (location) {
        filtered = filtered.filter(v => v.city === location);
    }
    
    displayVolunteers(filtered);
});
</script>
</body>
</html>