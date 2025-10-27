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
        <p><strong>Escriu la següent frase:</strong></p>
        <p id="frase"></p>
        <input type="text" id="inputOcult" autofocus/>
    </div>
    <form id="formRanking" action="ranking.php" method="POST" style="display:none;">
        <input type="hidden" name="frase" id="fraseInput">
        <input type="hidden" name="tiempo" id="tiempoInput">
    </form>

    <script src="./scriptPlay.js" defer></script>
</body>
</html>