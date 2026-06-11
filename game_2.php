<?php include 'includes/header.php'; ?>

<main>

<h1>Space Invaders</h1>

<script>
    const canvas = document.getElementById("gameCanvas");
const ctx = canvas.getContext("2d");

// Game variabelen
const player = {
    x: canvas.width / 2 - 20,
    y: canvas.height - 40,
    width: 40,
    height: 20,
    speed: 5
};

let bullets = [];
let invaders = [];
const invaderRows = 3;
const invaderCols = 8;
const invaderWidth = 35;
const invaderHeight = 20;
let invaderSpeed = 1;
let invaderDirection = 1; // 1 = rechts, -1 = links

// Toetsenbord status
const keys = {};

// 1. Maak de indringers (invaders) aan
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

// 2. Input luisteraars
window.addEventListener("keydown", (e) => keys[e.key] = true);
window.addEventListener("keyup", (e) => {
    keys[e.key] = false;
    // Zorg dat je maar 1 kogel per druk op de spatiebalk schiet
    if (e.key === " " || e.key === "ArrowUp") {
        bullets.push({ x: player.x + player.width / 2 - 2, y: player.y, width: 4, height: 10 });
    }
});

// 3. Update game objecten
function update() {
    // Speler bewegen
    if ((keys["ArrowLeft"] || keys["a"]) && player.x > 0) {
        player.x -= player.speed;
    }
    if ((keys["ArrowRight"] || keys["d"]) && player.x < canvas.width - player.width) {
        player.x += player.speed;
    }

    // Kogels bewegen
    bullets.forEach((bullet, index) => {
        bullet.y -= 7;
        // Verwijder kogel als hij van het scherm vliegt
        if (bullet.y < 0) bullets.splice(index, 1);
    });

    // Invaders bewegen
    let hitWall = false;
    invaders.forEach(invader => {
        invader.x += invaderSpeed * invaderDirection;
        // Check of een invader de rand raakt
        if (invader.x + invader.width > canvas.width || invader.x < 0) {
            hitWall = true;
        }
    });

    if (hitWall) {
        invaderDirection *= -1; // Draai richting om
        invaders.forEach(invader => invader.y += 15); // Zak een stukje naar beneden
    }

    // Botsing detectie (AABB Collision)
    bullets.forEach((bullet, bIndex) => {
        invaders.forEach((invader, iIndex) => {
            if (bullet.x < invader.x + invader.width &&
                bullet.x + bullet.width > invader.x &&
                bullet.y < invader.y + invader.height &&
                bullet.y + bullet.height > invader.y) {
                
                // Botsing! Verwijder kogel en invader
                setTimeout(() => {
                    invaders.splice(iIndex, 1);
                    bullets.splice(bIndex, 1);
                }, 0);
            }
        });
    });

    // Game over check (Als de invaders te laag komen)
    invaders.forEach(invader => {
        if (invader.y + invader.height >= player.y) {
            alert("Game Over! De aliens hebben de aarde veroverd.");
            document.location.reload();
        }
    });

    // Winst check
    if (invaders.length === 0) {
        alert("Gewonnen! Je hebt de aarde gered!");
        document.location.reload();
    }
}

// 4. Teken alles op het scherm
function draw() {
    // Maak het canvas leeg
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Teken speler (Groen)
    ctx.fillStyle = "#00FF00";
    ctx.fillRect(player.x, player.y, player.width, player.height);

    // Teken kogels (Rood)
    ctx.fillStyle = "#FF0000";
    bullets.forEach(bullet => {
        ctx.fillRect(bullet.x, bullet.y, bullet.width, bullet.height);
    });

    // Teken invaders (Wit)
    ctx.fillStyle = "#FFFFFF";
    invaders.forEach(invader => {
        ctx.fillRect(invader.x, invader.y, invader.width, invader.height);
    });
}

// 5. De hoofd Game Loop
function gameLoop() {
    update();
    draw();
    requestAnimationFrame(gameLoop);
}

// Start het spel
createInvaders();
gameLoop();


</script>
<canvas id="gameCanvas" width="600" height="500"></canvas>

</main>

<?php include 'includes/footer.php'; ?>