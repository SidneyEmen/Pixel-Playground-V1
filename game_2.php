<?php include 'includes/header.php'; ?>

<main>

<h1 class="SI-Text">Welkom bij Space Invaders!</h1>
<h3>Tip: Druk op F11 voor Fullscreen.</h3>

<canvas id="gameCanvas" width="1000" height="600"></canvas>

    <script>
        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");

// ZET HIER DE AFBEELDING NEER:
        const backgroundImage = new Image();
        backgroundImage.src = 'img/SpaceBackgroundSI.png';


        // Game variabelen
        const player = {
            x: canvas.width / 2 - 20,
            y: canvas.height - 60,
            width: 60,
            height: 42,
            speed: 4
            
        };

        let isGameActive = true;

        let bullets = [];
        let invaders = [];
        const invaderRows = 3;
        const invaderCols = 12;
        const invaderWidth = 35;
        const invaderHeight = 20;
        let invaderSpeed = 2;
        let invaderDirection = 1;
        let currentLevel = 1; // Start bij level 1

        const keys = {};

        function createInvaders() {
            invaders = [];
            
            invaderSpeed = 2 + (currentLevel - 1) * 1.5;

            for (let r = 0; r < invaderRows; r++) {
                for (let c = 0; c < invaderCols; c++) {
                    invaders.push({
                        x: c * (invaderWidth + 15) + 50,
                        y: r * (invaderHeight + 15) + 50,
                        width: invaderWidth,
                        height: invaderHeight
                    });
                }
            }
        }

        window.addEventListener("keydown", (e) => keys[e.key] = true);
        window.addEventListener("keyup", (e) => {
            keys[e.key] = false;
            if (e.key === " " || e.key === "ArrowUp") {
                bullets.push({ x: player.x + player.width / 2 - 2, y: player.y, width: 4, height: 10 });
            }
        });

        function update() {
            if ((keys["ArrowLeft"] || keys["a"]) && player.x > 0) {
                player.x -= player.speed;
            }
            if ((keys["ArrowRight"] || keys["d"]) && player.x < canvas.width - player.width) {
                player.x += player.speed;
            }

            bullets.forEach((bullet, index) => {
                bullet.y -= 7;
                if (bullet.y < 0) bullets.splice(index, 1);
            });

            let hitWall = false;
            invaders.forEach(invader => {
                invader.x += invaderSpeed * invaderDirection;
                if (invader.x + invader.width > canvas.width || invader.x < 0) {
                    hitWall = true;
                }
            });

            if (hitWall) {
                invaderDirection *= -1;
                invaders.forEach(invader => invader.y += 15);
            }

            bullets.forEach((bullet, bIndex) => {
                invaders.forEach((invader, iIndex) => {
                    if (bullet.x < invader.x + invader.width &&
                        bullet.x + bullet.width > invader.x &&
                        bullet.y < invader.y + invader.height &&
                        bullet.y + bullet.height > invader.y) {
                        
                        setTimeout(() => {
                            invaders.splice(iIndex, 1);
                            bullets.splice(bIndex, 1);
                        }, 0);
                    }
                });
            });

            invaders.forEach(invader => {
                if (invader.y + invader.height >= player.y) {
                    isGameActive = false;
                    alert("Game Over!");
                    document.location.reload();
                }
            });

           if (invaders.length === 0) {
        // Als Level 3 IS GEHAALD (dus de speler zou naar level 4 gaan)
        if (currentLevel === 3) {
            alert("Gefeliciteerd! Je hebt alle 3 de levels uitgespeeld en de aarde gered! 🏆");
            currentLevel = 1; // Reset naar level 1
            document.location.reload(); // Herlaad de pagina om helemaal overnieuw te beginnen
            return;
        }

        // Als level 1 of 2 is gehaald, gaan we gewoon door naar het volgende level:
        currentLevel++; 
        
        alert("Level " + (currentLevel - 1) + " gehaald! Bereid je voor op Level " + currentLevel);
        
        player.x = canvas.width / 2 - (player.width / 2);
        bullets = [];
        
        gameStarted = false; 
        createInvaders(); // Maakt de nieuwe rij(en) en berekent de nieuwe snelheid
    }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);

            // Speler (Klassiek Groen Ruimteschip)
            ctx.fillStyle = "#0062ff"; 

            const shipDesign = [
            [0,0,0,0,0,0,0,1,0,0,0,0,0,0,0], // Rij 1: De spitse neus
            [0,0,0,0,0,0,0,1,0,0,0,0,0,0,0], // Rij 2
            [0,0,0,0,0,0,1,1,1,0,0,0,0,0,0], // Rij 3
            [0,0,0,0,1,0,1,1,1,0,1,0,0,0,0], // Rij 4: Start van de zijflappen
            [0,0,0,0,1,0,1,1,1,0,1,0,0,0,0], // Rij 5
            [0,0,0,1,1,1,1,1,1,1,1,1,0,0,0], // Rij 6: Vleugels dijen uit
            [0,0,1,1,1,1,1,1,1,1,1,1,1,0,0], // Rij 7
            [0,1,1,1,1,1,1,1,1,1,1,1,1,1,0], // Rij 8
            [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1], // Rij 9: Breedste punt
            [0,1,1,1,1,1,1,1,1,1,1,1,1,1,0], // Rij 10: Vleugeltips lopen schuin af
            [0,0,0,0,0,1,1,1,1,1,0,0,0,0,0], // Rij 11: Onderkant romp
            [0,0,0,0,0,1,1,0,1,1,0,0,0,0,0], // Rij 12: Ruimte voor de motoren
            [0,0,0,0,0,1,1,0,1,1,0,0,0,0,0]
           
         ];


    const pixelWidth = player.width / 15;
    const pixelHeight = player.height / 13;

    for (let row = 0; row < shipDesign.length; row++) {
        for (let col = 0; col < shipDesign[row].length; col++) {
            if (shipDesign[row][col] === 1) {
                ctx.fillRect(
                    player.x + (col * pixelWidth),
                    player.y + (row * pixelHeight),
                    pixelWidth + 0.4, // Kleine overlap om streepjes te voorkomen
                    pixelHeight + 0.4
                );
            }
        }
    }
            // Speler (Groen)


            // Kogels (Rood)
            ctx.fillStyle = "#FF0000";
            bullets.forEach(bullet => {
                ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
            });

            // Invaders (Wit)
            const invaderDesign = [
            [0,0,0,0,0,1,1,1,0,0,0,0,0], // Rij 1: Bovenkant antenne
            [0,0,0,0,0,1,1,1,0,0,0,0,0], // Rij 2: Onderkant antenne
            [0,0,0,0,1,1,1,1,1,0,0,0,0], // Rij 3: Schouderaanzet midden
            [1,1,0,0,1,1,1,1,1,0,0,1,1], // Rij 4: Toppen van de zijpoten
            [1,1,1,1,1,1,1,1,1,1,1,1,1], // Rij 5: Volledig brede bovenkant
            [1,1,1,1,1,1,1,1,1,1,1,1,1], // Rij 6: Volledig brede middenstuk
            [1,1,0,0,1,1,1,1,1,0,0,1,1], // Rij 7: Uitsnijding onderkant zijkanten
            [1,1,0,0,1,0,1,0,1,0,0,1,1], // Rij 8: De "witte boze ogen" (0 = leeglaten)
            [1,1,0,0,1,1,1,1,1,0,0,1,1], // Rij 9: Onderkant middenschip
            [1,1,0,0,0,0,0,0,0,0,0,1,1], // Rij 10: Losstaande poten onderkant
            [1,1,0,0,0,0,0,0,0,0,0,1,1]
          
];

ctx.fillStyle = "#8208d9"; // Jouw paarse kleur
    invaders.forEach(invader => {
        // Bereken hoe groot elke pixel van de alien moet zijn
        const pixelWidth = invader.width / 12;  // 12 kolommen breed
        const pixelHeight = invader.height / 10; // 10 rijen hoog

        for (let row = 0; row < invaderDesign.length; row++) {
            for (let col = 0; col < invaderDesign[row].length; col++) {
                if (invaderDesign[row][col] === 1) {
                    ctx.fillRect(
                        invader.x + (col * pixelWidth),
                        invader.y + (row * pixelHeight),
                        pixelWidth + 0.4,  // Kleine overlap tegen streepjes
                        pixelHeight + 0.4
                    );
                }
            }
        }
    });

        

   
        }

        function gameLoop() {

            if (!isGameActive) return;

            update();
            draw();
            requestAnimationFrame(gameLoop);
        }

        createInvaders();
        gameLoop();
    
</script>



</main>

<?php include 'includes/footer.php'; ?>