# 🦸‍♂️ MarvelType

**MarvelType** es un juego de mecanografía web ambientado en el universo Marvel.  
El jugador puede elegir su nivel de dificultad, escribir su nombre y demostrar su velocidad y precisión escribiendo frases correctamente.  
Al finalizar, puede guardar su puntuación en el ranking y competir contra otros jugadores.

---

## 🌐 Enlace al proyecto publicado

👉 Puedes jugar directamente desde aquí:  
**[https://cibertype2.ieti.site/Projecto-CyberType/](https://cibertype2.ieti.site/Projecto-CyberType/)**

---

## 🎯 Objetivo del juego
El objetivo es escribir correctamente las frases que aparecen en pantalla lo más rápido posible.  
Cada nivel de dificultad modifica la velocidad o longitud de las frases.  
Cuando el jugador termina todas las frases, accede a la pantalla final donde puede:
- Guardar su puntuación en el ranking.
- Volver a jugar desde el inicio.

---

## 🧩 Tecnologías utilizadas
- **HTML5**, **CSS3** y **JavaScript** para la parte visual e interactiva.
- **PHP** para la gestión del juego, navegación entre páginas y almacenamiento del ranking.
- **Archivos locales** (`ranking.txt`, `sentences.txt`) para guardar datos de jugadores y frases.
- **Sonidos y efectos** (`snap.mp3`, `gameover.wav`, etc.) para mejorar la experiencia de juego.

---

## ⚙️ Instalación y puesta en marcha

### 1️⃣ Requisitos previos
- Servidor local con soporte **PHP** (por ejemplo: [XAMPP](https://www.apachefriends.org/es/index.html), [Laragon](https://laragon.org/), o similar).  
- Navegador web actualizado (Chrome, Firefox, Edge, etc.).

### 2️⃣ Instalación
1. Clona el repositorio o descárgalo como ZIP:
   ```bash
   git clone https://github.com/Dpcrack22/Projecto-CyberType.git
   ```
2. Coloca la carpeta del proyecto dentro del directorio raíz de tu servidor web:
   - En XAMPP → `C:\xampp\htdocs\`
   - En Laragon → `C:\laragon\www\`
3. Asegúrate de tener todos los archivos en la misma carpeta (`index.php`, `play.php`, `gameover.php`, `ranking.php`, `sentences.txt`, `ranking.txt`, etc.).

### 3️⃣ Ejecución
1. Inicia tu servidor Apache (por ejemplo, desde el panel de XAMPP).
2. Abre tu navegador y entra en:
   ```
   http://localhost/Projecto-CyberType/PRO/index.php
   ```

---

## 🧪 Desarrollo con frontend React (Vite) + backend PHP

Esta opción es útil si estás migrando la interfaz a React manteniendo el backend actual en PHP.

1. Levanta el backend PHP (desde la raíz del repo):
   ```bash
   php -S localhost:8000 -t .
   ```
2. Levanta el frontend React (en otra terminal):
   ```bash
   cd frontend
   npm install
   npm run dev
   ```
3. Desde React, llama a los endpoints PHP usando el prefijo `/backend` (proxy de Vite), por ejemplo:
   - `fetch('/backend/get_sentence.php')`

---

## 🕹️ Instrucciones de juego

1. **Pantalla inicial (index.php):**  
   - Elige el nivel de dificultad: *Fácil*, *Medio* o *Difícil*.  
   - Escribe tu nombre de jugador.  
   - Pulsa **Start** para comenzar.

2. **Pantalla de juego (play.php):**  
   - Aparecerá una frase que debes escribir exactamente igual.  
   - Si la escribes correctamente, pasarás automáticamente a la siguiente.  

3. **Pantalla final (gameover.php):**  
   - Muestra tu puntuación final.  
   - Puedes elegir entre **Guardar en el ranking** o **Jugar de nuevo**.  

4. **Ranking (ranking.php):**  
   - Se muestran los nombres y puntuaciones de todos los jugadores guardados.  

---

## 👨‍💻 Autores y contribución

Proyecto desarrollado en equipo para el módulo de **Desenvolupament d’Aplicacions Web (DAW)**.

Cada integrante del equipo ha contribuido con un mínimo de **4 commits** en el repositorio GitHub.

Repositorio Git:  
🔗 [https://github.com/Dpcrack22/Projecto-CyberType](https://github.com/Dpcrack22/Projecto-CyberType)

---

## 🧾 Licencia
Este proyecto se publica con fines educativos.  
Todos los derechos de los personajes y referencias pertenecen a **Marvel Studios**.  
El código puede reutilizarse y modificarse con fines no comerciales, mencionando la autoría original.

---

## 🚀 Estado del proyecto
Versión actual: **PRO (versión final estable)**  
Última actualización: *Octubre 2025*
