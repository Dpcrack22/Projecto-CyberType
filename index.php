<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - MarvelType</title>
    <link rel="stylesheet" type="text/css" href="./styles.css?<?php echo time(); ?>" />
    <script src="script.js" defer></script>
</head>
<body class="body-index">
    <div class="hero-container">
        <img src="/IMG/IndexImg001.png" alt="Iron Man" class="side-img left-img">

        <section>
            <h1>MarvelType</h1>
            <p id="gameDescription">Joc de mecanografia. L'objectiu consisteix en teclejar correctament la frase que proporciona el joc en el menys temps possible i sense errades.</p>
            <div id="nameArea">
                <input type="text" id="inputName" placeholder="Insert your name"/>
                <p id="infoText"></p>
            </div>
            <select id="selectDifficulty">
                <option value="easy">Easy</option>
                <option value="medium">Medium</option>
                <option value="hard">Hard</option>
            </select>
            <br>
            <input type="button" id="startGameButton" value="Start Game"/>
        </section>

        <img src="/IMG/IndexImg002.png" alt="Thanos" class="side-img right-img">
    </div>
</body>
</html>