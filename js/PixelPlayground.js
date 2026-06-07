// Event Bubbling
const container = document.getElementById('games-container');

container.addEventListener('click', (event) => {
    const game = event.target.closest('.game');
    if (!game) return;
});


// Reaction_Game (Check reaction_time.php)
