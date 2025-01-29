<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SIMAWI</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .main-container {
            min-height: calc(100vh - 70px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 400px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .auth-card .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 20px;
        }

        .auth-card .card-body {
            padding: 30px;
        }

        .navbar {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #0d6efd !important;
        }

        .brand-icon {
            font-size: 1.8rem;
            margin-right: 10px;
            vertical-align: middle;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-size: 0.875rem;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
        }

        .btn-primary {
            padding: 12px;
            border-radius: 8px;
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->


    <!-- Main Content -->
    <div class="main-container">
        <div class="container">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>