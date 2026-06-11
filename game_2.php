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
            y: canvas.height - 40,
            width: 40,
            height: 20,
            speed: 5
            
        };

        let isGameActive = true;

        let bullets = [];
        let invaders = [];
        const invaderRows = 3;
        const invaderCols = 8;
        const invaderWidth = 35;
        const invaderHeight = 20;
        let invaderSpeed = 3;
        let invaderDirection = 1;

        const keys = {};

        function createInvaders() {
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
                isGameActive = false;
                alert("Gewonnen!");
                document.location.reload();
            }
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Speler (Groen)
            ctx.fillStyle = "#007bff";
            ctx.fillRect(player.x, player.y, player.width, player.height);

            // Kogels (Rood)
            ctx.fillStyle = "#FF0000";
            bullets.forEach(bullet => {
                ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
            });

            // Invaders (Wit)
            ctx.fillStyle = "#8208d9";
            invaders.forEach(invader => {
                ctx.fillRect(invader.x, invader.y, invader.width, invader.height);
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