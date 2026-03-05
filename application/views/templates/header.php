<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $title; ?></title>

    <link href="<?= base_url('assets/')?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="<?= base_url('assets/')?>css/sb-admin-2.min.css" rel="stylesheet">
    
    <link href="<?= base_url('assets/')?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <style>
        /* Warna Badge Custom yang lebih modern */
        /* Tambahkan atau pastikan ini ada di header.php */
        .badge-received   { background-color: #eaecf4; color: #5a5c69; } 
        .badge-processing { background-color: #ddf3ff; color: #0984e3; } 
        .badge-ready      { background-color: #fff4e0; color: #d35400; } 
        .badge-shipped    { background-color: #e3f2fd; color: #1976d2; } 
        .badge-completed  { background-color: #e8f5e9; color: #2e7d32; } 
        .badge-rejected   { background-color: #ffebee; color: #c62828; } 
        .badge-checking   { background-color: #e0f7fa; color: #00838f; } /* Warna tambahan untuk Checking */
        
        .badge {
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
        }
        .timeline { position: relative; padding-left: 30px; list-style: none; }
        .timeline:before { 
            content: ""; position: absolute; left: 10px; top: 0; bottom: 0; 
            width: 2px; background: #e3e6f0; 
        }
        .timeline-item { position: relative; margin-bottom: 20px; }
        .timeline-marker { 
            position: absolute; left: -25px; top: 5px; width: 12px; height: 12px; 
            border-radius: 50%; background: #4e73df; border: 2px solid #fff; shadow: 0 0 0 3px #e3e6f0;
        }
        .timeline-item.active .timeline-marker { background: #1cc88a; shadow: 0 0 0 3px #d1f2e8; }
        .timeline-content { padding-bottom: 10px; border-bottom: 1px dashed #e3e6f0; }
        .timeline-date { font-size: 0.75rem; color: #858796; font-weight: bold; }
        .italic { font-style: italic; }
    </style>
</head>
<body id="page-top">
    <div id="wrapper">