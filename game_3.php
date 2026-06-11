<?php include 'includes/header.php'; ?>

<main class="hotdog-layout">

    <h2 class="hotdog-score">Hotdogs: 0</h2>

    <section class="hotdog-box">
        <p class="hotdog-text">Click the hotdog!</p>
    </section>

</main>

<style>

.hotdog-layout {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 30px;
    margin-top: 60px;
}

.hotdog-score {
    font-size: 2rem;
    color: var(--white);
}

.hotdog-box {
    width: 400px;
    height: 250px;
    background-color: var(--black);
    border-radius: 12px;

    display: flex;
    justify-content: center;
    align-items: center;

    cursor: pointer;
}

.hotdog-text {
    color: var(--white);
    font-size: 1.6rem;
}

</style>
<script>

    const box = document.querySelector('.hotdog-box');
const scoreText = document.querySelector('.hotdog-score');

let score = 0;

box.addEventListener('click', () => {
    score++;
    scoreText.textContent = `Hotdogs: ${score}`;
});

</script>

<?php include 'includes/footer.php'; ?>