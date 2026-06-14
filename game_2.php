<?php include 'includes/header.php'; ?>

<main>

<h1 class="SI-Text">Welkom bij Space Invaders!</h1>
<h3>Tip: Druk op F11 voor Fullscreen.</h3>

<canvas id="gameCanvas" width="1000" height="600"></canvas>

    <script>
        const canvas = document.getElementById("gameCanvas");
        const ctx = canvas.getContext("2d");


        const backgroundImage = new Image();
        backgroundImage.src = 'img/SpaceBackgroundSI.png';


     
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

        const invaderWidth = 35;
        const invaderHeight = 20;

        let invaderSpeed = 2;
        let invaderDirection = 1;
        let currentLevel = 1;
        let lastShotTime = 0;
        let score = 0; 
        let highscore = localStorage.getItem("spaceInvadersHighscore") || 0;

        const shotDelay = 100; 
        
        const keys = {};

       

        function createInvaders() {
            invaders = [];
            
            invaderSpeed = 2 + (currentLevel - 1) * 1.5;

            let rows = 4; 
            let cols = 8; 

            if (currentLevel === 2) {
                rows = 6; 
                cols = 10; 
            } else if (currentLevel === 3) {

                rows = 6; 
                cols = 12;
           }

            for (let r = 0; r < rows; r++) {
                for (let c = 0; c < cols; c++) {
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

  
    if (keys[" "] || keys["ArrowUp"]) {
        const now = Date.now();
        if (now - lastShotTime > shotDelay) {
            bullets.push({ 
                x: player.x + player.width / 2 - 2, 
                y: player.y, 
                width: 4, 
                height: 10 
            });
            lastShotTime = now; 
        }
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
                            score += 10;
                        }, 0);
                    }
                });
            });

          for (let invader of invaders) {
       
        if (isGameActive && (invader.y + invader.height >= player.y)) {
            isGameActive = false; 
            alert("Game Over!");

           
            if (score > highscore) {
                highscore = score;
                localStorage.setItem("spaceInvadersHighscore", highscore);
            }

           
            currentLevel = 1;
            score = 0;
            bullets = [];
            player.x = canvas.width / 2 - player.width / 2;
        
            isGameActive = true;          
            return;
        }
    }

    if (invaders.length === 0 && isGameActive) {
       
    if (currentLevel === 3) {
            
            isGameActive = false;
            alert("Gefeliciteerd! Je hebt alle 3 de levels uitgespeeld en de aarde gered! 🚀");

            if (score > highscore) {
                highscore = score;
                localStorage.setItem("spaceInvadersHighscore", highscore);
            }

            document.location.reload(); 
    }
     
        } else {
        currentLevel++; 
        
       
        alert("Level " + (currentLevel - 1) + " gehaald! Bereid je voor op Level " + currentLevel);
        
        
        player.x = canvas.width / 2 - (player.width / 2);
        
      
        bullets = [];
        invaderDirection = 1;
        
        
        createInvaders();
        
       
        }
    }


        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(backgroundImage, 0, 0, canvas.width, canvas.height);

          
            ctx.fillStyle = "#0062ff"; 

            const shipDesign = [
            [0,0,0,0,0,0,0,1,0,0,0,0,0,0,0], 
            [0,0,0,0,0,0,0,1,0,0,0,0,0,0,0], 
            [0,0,0,0,0,0,1,1,1,0,0,0,0,0,0], 
            [0,0,0,0,1,0,1,1,1,0,1,0,0,0,0], 
            [0,0,0,0,1,0,1,1,1,0,1,0,0,0,0], 
            [0,0,0,1,1,1,1,1,1,1,1,1,0,0,0], 
            [0,0,1,1,1,1,1,1,1,1,1,1,1,0,0], 
            [0,1,1,1,1,1,1,1,1,1,1,1,1,1,0], 
            [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1], 
            [0,1,1,1,1,1,1,1,1,1,1,1,1,1,0], 
            [0,0,0,0,0,1,1,1,1,1,0,0,0,0,0], 
            [0,0,0,0,0,1,1,0,1,1,0,0,0,0,0], 
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
                    pixelWidth + 0.4, 
                    pixelHeight + 0.4
                );
            }
        }
    }
          


           
            ctx.fillStyle = "#FF0000";
            bullets.forEach(bullet => {
                ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
            });

            
            const invaderDesign = [
            [0,0,0,0,0,1,1,1,0,0,0,0,0], 
            [0,0,0,0,0,1,1,1,0,0,0,0,0], 
            [0,0,0,0,1,1,1,1,1,0,0,0,0], 
            [1,1,0,0,1,1,1,1,1,0,0,1,1], 
            [1,1,1,1,1,1,1,1,1,1,1,1,1],
            [1,1,1,1,1,1,1,1,1,1,1,1,1], 
            [1,1,0,0,1,1,1,1,1,0,0,1,1], 
            [1,1,0,0,1,0,1,0,1,0,0,1,1], 
            [1,1,0,0,1,1,1,1,1,0,0,1,1], 
            [1,1,0,0,0,0,0,0,0,0,0,1,1], 
            [1,1,0,0,0,0,0,0,0,0,0,1,1]
          
];

ctx.fillStyle = "#8208d9"; 
    invaders.forEach(invader => {
       
        const pixelWidth = invader.width / 12;  
        const pixelHeight = invader.height / 10; 

        for (let row = 0; row < invaderDesign.length; row++) {
            for (let col = 0; col < invaderDesign[row].length; col++) {
                if (invaderDesign[row][col] === 1) {
                    ctx.fillRect(
                        invader.x + (col * pixelWidth),
                        invader.y + (row * pixelHeight),
                        pixelWidth + 0.4,  
                        pixelHeight + 0.4
                    );
                }
            }
        }
    });

       
    ctx.font = "20px 'Courier New', Courier, monospace";
    ctx.fillStyle = "white";
    
   
    ctx.fillText("Score: " + score, 20, 40);
    ctx.fillText("Level: " + currentLevel, 20, 70);
    

    ctx.textAlign = "right";
    ctx.fillText("Highscore: " + highscore, canvas.width - 20, 40);
    ctx.textAlign = "left"; 

   
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