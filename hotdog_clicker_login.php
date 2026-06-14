<?php
session_start();

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<?php include 'includes/header_login.php'; ?>

<main class="hotdog-wrapper">

    <!-- Linker menu -->
    <section class="upgrade-menu left-menu">
        <h3>Upgrades</h3>
        <article class="upgrade-item">Upgrade 1 (+1 per klik)</article>
        <article class="upgrade-item">Upgrade 2</article>
    </section>

    <!-- Hotdog -->
    <section class="hotdog-center">
        <h2 class="hotdog-score">Hotdogs: 0</h2>
        <article class="hotdog-box">
            <img src="img/hotdog.png" alt="Hotdog" class="hotdog-img">
        </article>
    </section>

    <!-- Rechter menu -->
    <section class="upgrade-menu right-menu">
        <h3>Upgrades</h3>
        <article class="upgrade-item">Upgrade 3</article>
        <article class="upgrade-item">Upgrade 4</article>
    </section>

    <!-- Upgrade 1 -->
    <template id="upgrade1-template">
        <div class="upgrade-popup">
            <div class="upgrade-option" data-power="1" data-cost="50">+1 per click (Costs 50)</div>
            <div class="upgrade-option" data-power="5" data-cost="200">+5 per click (Costs 200)</div>
            <div class="upgrade-option" data-power="20" data-cost="1000">+20 per click (Costs 1000)</div>
        </div>
    </template>

    <!-- Upgrade 2 -->
    <template id="upgrade2-template">
        <div class="upgrade-popup">
            <div class="upgrade-option" data-power="1" data-cost="100">+1/sec (Costs 100)</div>
            <div class="upgrade-option" data-power="5" data-cost="400">+5/sec (Costs 400)</div>
            <div class="upgrade-option" data-power="20" data-cost="1500">+20/sec (Costs 1500)</div>
        </div>
    </template>

    <!-- Upgrade 3 -->
    <template id="upgrade3-template">
        <div class="upgrade-popup">
            <div class="upgrade-option" data-multiplier="2" data-cost="500">x2 multiplier (Costs 500)</div>
            <div class="upgrade-option" data-multiplier="5" data-cost="2000">x5 multiplier (Costs 2000)</div>
            <div class="upgrade-option" data-multiplier="10" data-cost="10000">x10 multiplier (Costs 10000)</div>
        </div>
    </template>

    <!-- Upgrade 4 -->
    <template id="upgrade4-template">
        <div class="upgrade-popup">
            <div class="upgrade-option" data-multiplier="2" data-cost="1000">30 sec x2 boost (Costs 1000)</div>
            <div class="upgrade-option" data-multiplier="3" data-cost="5000">30 sec x3 boost (Costs 5000)</div>
            <div class="upgrade-option" data-multiplier="5" data-cost="20000">30 sec x5 boost (Costs 20000)</div>
        </div>
    </template>
</main>

<style>
.hotdog-wrapper {
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    gap: 60px;
}

.upgrade-menu {
    width: 200px;
    background-color: var(--black);
    padding: 20px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.upgrade-menu h3 {
    color: var(--white);
    margin-bottom: 10px;
}

.upgrade-item {
    color: var(--black);
    background-color: var(--white);
    padding: 12px;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
}

.hotdog-center {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 25px;
    text-align: center;
}

.hotdog-score {
    font-size: 2rem;
    color: var(--white);
}

.hotdog-box {
    width: 400px;
    height: 250px;
    background-color: transparent;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
}

.hotdog-img {
    width: 500px;
    height: auto;
    cursor: pointer;
    transition: transform 0.1s ease-in-out;
}

.hotdog-img:active {
    transform: scale(0.95);
}

.upgrade-popup {
    position: absolute;
    background-color: var(--black);
    color: var(--white);
    border-radius: 8px;
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
    z-index: 10;
    animation: fadeIn 0.2s ease-in-out;
}

.upgrade-option {
    background-color: var(--white);
    color: var(--black);
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    text-align: center;
}

.upgrade-option:hover {
    background-color: #ddd;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

</style>

<script>
const box = document.querySelector('.hotdog-box');
const scoreText = document.querySelector('.hotdog-score');
const upgrade1 = document.querySelectorAll('.upgrade-item')[0];
const upgrade2 = document.querySelectorAll('.upgrade-item')[1];

const template1 = document.querySelector('#upgrade1-template');
const template2 = document.querySelector('#upgrade2-template');

let score = 0;
let clickPower = 1;
let autoPower = 0;

let popup1 = null;
let popup2 = null;

let clickMultiplier = 1;
let popup3 = null;

let activeBoost = 1;
let boostTimeout = null;
let popup4 = null;

// Hotdog click
box.addEventListener('click', () => {
    score += clickPower * clickMultiplier * activeBoost;
    updateScore();
});

function updateScore() {
    scoreText.textContent = `Hotdogs: ${score}`;
}

// Upgrade 1 submenu
upgrade1.addEventListener('click', () => {

    // Sluit popup 2 als die open is
    if (popup2) {
        popup2.remove();
        popup2 = null;
    }

    // Als popup 1 al open is → sluit hem
    if (popup1) {
        popup1.remove();
        popup1 = null;
        return;
    }

    const clone = template1.content.cloneNode(true);
    popup1 = clone.querySelector('.upgrade-popup');

    const rect = upgrade1.getBoundingClientRect();
    popup1.style.top = `${rect.bottom + window.scrollY + 10}px`;
    popup1.style.left = `${rect.left + window.scrollX}px`;

    document.body.appendChild(popup1);

    popup1.querySelectorAll('.upgrade-option').forEach(option => {
        option.addEventListener('click', () => {
            const power = +option.dataset.power;
            const cost = +option.dataset.cost;

            if (score >= cost) {
                score -= cost;
                clickPower += power;
                updateScore();
                upgrade1.innerHTML = `Upgrade 1 (Click)<br>Click-Power: ${clickPower}`;
            } else {
                option.textContent = `Not enough hotdogs!`;
                setTimeout(() => option.textContent = `+${power} per click (Costs ${cost})`, 1500);
            }
        });
    });
});

// Upgrade 2 submenu
upgrade2.addEventListener('click', () => {

    if (popup1) {
        popup1.remove();
        popup1 = null;
    }

    if (popup2) {
        popup2.remove();
        popup2 = null;
        return;
    }

    const clone = template2.content.cloneNode(true);
    popup2 = clone.querySelector('.upgrade-popup');

    const rect = upgrade2.getBoundingClientRect();
    popup2.style.top = `${rect.bottom + window.scrollY + 10}px`;
    popup2.style.left = `${rect.left + window.scrollX}px`;

    document.body.appendChild(popup2);

    popup2.querySelectorAll('.upgrade-option').forEach(option => {
        option.addEventListener('click', () => {
            const power = +option.dataset.power;
            const cost = +option.dataset.cost;

            if (score >= cost) {
                score -= cost;
                autoPower += power;
                updateScore();
                upgrade2.innerHTML = `Upgrade 2 (Auto)<br>Auto-Power: ${autoPower}`;
            } else {
                option.textContent = `Not enough hotdogs!`;
                setTimeout(() => option.textContent = `+${power}/sec (Costs ${cost})`, 1500);
            }
        });
    });
});

const upgrade3 = document.querySelectorAll('.upgrade-item')[2];
const template3 = document.querySelector('#upgrade3-template');

// Upgrade 3 submenu
upgrade3.addEventListener('click', () => {

    if (popup1) { popup1.remove(); popup1 = null; }
    if (popup2) { popup2.remove(); popup2 = null; }

    if (popup3) {
        popup3.remove();
        popup3 = null;
        return;
    }

    const clone = template3.content.cloneNode(true);
    popup3 = clone.querySelector('.upgrade-popup');

    const rect = upgrade3.getBoundingClientRect();
    popup3.style.top = `${rect.bottom + window.scrollY + 10}px`;
    popup3.style.left = `${rect.left + window.scrollX}px`;

    document.body.appendChild(popup3);

    popup3.querySelectorAll('.upgrade-option').forEach(option => {
        option.addEventListener('click', () => {
            const mult = parseInt(option.dataset.multiplier);
            const cost = parseInt(option.dataset.cost);

            if (score >= cost) {
                score -= cost;
                clickMultiplier *= mult;
                updateScore();
                upgrade3.innerHTML = `Upgrade 3 (Multiplier)<br>Multiplier: x${clickMultiplier}`;
            } else {
                option.textContent = `Not enough hotdogs!`;
                setTimeout(() => {
                    option.textContent = `x${mult} multiplier (Costs ${cost})`;
                }, 1500);
            }
        });
    });
});

const upgrade4 = document.querySelectorAll('.upgrade-item')[3];
const template4 = document.querySelector('#upgrade4-template');

// Upgrade 4 — Time Booster submenu
upgrade4.addEventListener('click', () => {

    if (popup1) { popup1.remove(); popup1 = null; }
    if (popup2) { popup2.remove(); popup2 = null; }
    if (popup3) { popup3.remove(); popup3 = null; }

    if (popup4) {
        popup4.remove();
        popup4 = null;
        return;
    }

    const clone = template4.content.cloneNode(true);
    popup4 = clone.querySelector('.upgrade-popup');

    const rect = upgrade4.getBoundingClientRect();
    popup4.style.top = `${rect.bottom + window.scrollY + 10}px`;
    popup4.style.left = `${rect.left + window.scrollX}px`;

    document.body.appendChild(popup4);

    popup4.querySelectorAll('.upgrade-option').forEach(option => {
        option.addEventListener('click', () => {
            const boost = parseInt(option.dataset.multiplier);
            const cost = parseInt(option.dataset.cost);

            if (score >= cost) {
                score -= cost;
                updateScore();

                // Reset bestaande boost
                if (boostTimeout) clearTimeout(boostTimeout);

                activeBoost = boost;
                upgrade4.innerHTML = `Upgrade 4 (Boost)<br>Boost: x${boost}`;

                // Boost eindigt na 30 seconden
                boostTimeout = setTimeout(() => {
                    activeBoost = 1;
                    upgrade4.innerHTML = `Upgrade 4 (Boost)<br>Boost Expired`;
                }, 30000);

            } else {
                option.textContent = `Not enough hotdogs!`;
                setTimeout(() => {
                    option.textContent = `30 sec x${boost} boost (Costs ${cost})`;
                }, 1500);
            }
        });
    });
});

// Auto income
setInterval(() => {
    score += autoPower * activeBoost;
    updateScore();
}, 1000);

upgrade1.innerHTML = `Upgrade 1 (Click)<br>Click-Power: ${clickPower}`;
upgrade2.innerHTML = `Upgrade 2 (Auto)<br>Auto-Power: ${autoPower}`;
upgrade3.innerHTML = `Upgrade 3 (Multiplier)<br>Multiplier: x${clickMultiplier}`;
upgrade4.innerHTML = `Upgrade 4 (Boost)<br>Boost: None`;
</script>

<?php include 'includes/footer.php'; ?>