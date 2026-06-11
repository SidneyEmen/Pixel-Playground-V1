<?php include 'includes/header.php'; ?>

<main class="reaction-layout">

    <section class="reaction-text">
        <h2>Reaction Time</h2>
        <p>Click as soon as the red area turns green!</p>
    </section>

    <section class="reaction-box">
        <p class="reaction-info">Click to start!</p>
        <button id="restart-button" style="display:none;">Restart</button>
    </section>

<script>
    
const box = document.querySelector('.reaction-box');
const info = document.querySelector('.reaction-info');
const restartButton = document.getElementById('restart-button');

let greenTime = 0;
let isGreen = false;
let gameOver = false;

box.addEventListener('click', () => {

    if (gameOver) return;

    if (isGreen) {
        const reaction = Date.now() - greenTime;
        info.textContent = `Your time: ${reaction} ms`;
        box.style.backgroundColor = 'black';

        isGreen = false;
        gameOver = true;

        restartButton.style.display = 'block';
        return;
    }

    box.style.backgroundColor = 'red';
    info.textContent = 'Wait for green...';

    startGreenTimer();
});


function startGreenTimer() {
    const delay = Math.random() * 4000 + 2000;

    setTimeout(() => {
        box.style.backgroundColor = 'green';
        info.textContent = 'CLICK!';

        greenTime = Date.now();
        isGreen = true;

    }, delay);
}


restartButton.addEventListener('click', () => {
    restartButton.style.display = 'none';
    gameOver = false;
});

</script>
</main>

<?php include 'includes/footer.php'; ?> 