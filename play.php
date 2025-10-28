<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Play - CyberType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
</head>
<body id="bodyPlay">
    <h1>Preparat!</h1>
    <div id="contador">3</div>
    <div id="fraseContainer">
        <p><strong>Escriu a següent frase:</strong></p>
        <p id="frase"></p>
        <input type="text" id="inputOcult" autofocus/>
    </div>
    <script src="./scriptPlay.js" defer></script>
</body>
</html>