<!DOCTYPE html>
<html lang="es">
<head>
    <?php include(sprintf('%s/partials/head.php', dirname(__FILE__, 1))) ?>
</head>
<body>
    <?php include(sprintf('%s/partials/header.php', dirname(__FILE__, 1))) ?>

    <main>
        <?php require_once $content ?>
    </main>

    <?php include(sprintf('%s/partials/footer.php', dirname(__FILE__, 1))) ?>
    <?php include(sprintf('%s/partials/scripts.php', dirname(__FILE__, 1))) ?>
        
</body>
</html>