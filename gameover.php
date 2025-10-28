<?php
    session_start();
    if (!isset($_SESSION['game_finished']) || $_SESSION['game_finished'] !== true) {
        header("Location: error403.php");
        exit;
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameOver - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body>
    
</body>
</html>