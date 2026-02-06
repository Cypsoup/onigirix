<?php
require_once 'config/db.php';
require_once 'includes/functions.php';
$activePage = 'homePage';
?>

<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OnigiriX</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="h-full bg-white text-black font-sans">

    <div class="flex h-full w-full">

        <!-- SIDEBAR -->
        <?php include 'includes/sidebar.php'; ?>


    </div>

    <!-- Javascript -->
    <script src="js/main.js"></script>
</body>

</html>