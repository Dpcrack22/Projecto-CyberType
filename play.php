<?php
    session_start();

    if (isset($_POST['playerName'])) {
        $_SESSION['playerName'] = htmlspecialchars($_POST['playerName']);
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play - CyberType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body class="body-play">
    <h1 id="titulo-play">MarvelType</h1>
    <h1 id="titulo-prepara">Preparat!</h1>
    <div id="contador">3</div>
    <div id="fraseContainer">
        <p><strong>Escriu la següent frase:</strong></p>
        <p id="frase"></p>
        <input type="text" id="inputOcult" autofocus autocomplete="off"/>
    </div>

    <script src="./scriptPlay.js" defer></script>
</body>
</html>