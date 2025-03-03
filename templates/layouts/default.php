<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Baratie') ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded" rel="stylesheet"/>
</head>
<?php require ROOT_DIR . '/templates/partials/header.php'; ?>
<body>
    <?php require ROOT_DIR . "/templates/$template.php"; ?>
    
    <script src="/assets/js/script.js"></script>
</body>
</html>