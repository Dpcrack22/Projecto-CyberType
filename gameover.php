<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameOver - CyberType</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    


    <?php
        session_start();
        if (!isset($_SESSION['game_finished']) || $_SESSION['game_finished'] !== true) {
            header("Location: error403.php");
            exit;
        }
    ?>

</body>
</html>