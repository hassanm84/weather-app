<?php

// partial view for header section
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="/vendor/bootstrap/css/bootstrap.min.css">
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
    <!-- CSS for layouts -->
    <link rel="stylesheet" href="/css/header.css">
    <link rel="stylesheet" href="/css/footer.css">
    <link rel="stylesheet" href="/css/home.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/js/app.js"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar bg-body-tertiary shadow-sm">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap">
            <a class="navbar-brand" href="#">
                <img src="/images/bright-sg-logo-dark.svg" alt="Bright SG Logo" class="d-inline-block align-text-top" height="40">
            </a>
            <h3>Weather Information Application</h3>
        </div>
    </nav>
    <!-- Start of main section -->
    <main class="container">