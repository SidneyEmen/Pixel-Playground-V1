<?php include 'includes/header.php'; ?>

<main class="hotdog-wrapper">

    <!-- Linker upgrade menu -->
    <section class="upgrade-menu left-menu">
        <h3>Upgrades</h3>
        <article class="upgrade-item">Upgrade 1 (+1 per klik)</article>
        <article class="upgrade-item">Upgrade 2</article>
    </section>

    <!-- Midden: Hotdog clicker -->
    <section class="hotdog-center">
        <h2 class="hotdog-score">Hotdogs: 0</h2>

        <article class="hotdog-box">
            <p class="hotdog-text">Click the hotdog!</p>
        </article>
    </section>

    <!-- Rechter upgrade menu -->
    <section class="upgrade-menu right-menu">
        <h3>Upgrades</h3>
        <article class="upgrade-item">Upgrade 3</article>
        <article class="upgrade-item">Upgrade 4</article>
    </section>

</main>

<!-- Template voor submenu van Upgrade 1 -->
<template id="upgrade1-template">
    <div class="upgrade-popup">
        <div class="upgrade-option" data-power="1" data-cost="50">+1 per klik (kost 50)</div>
        <div class="upgrade-option" data-power="5" data-cost="200">+5 per klik (kost 200)</div>
        <div class="upgrade-option" data-power="20" data-cost="1000">+20 per klik (kost 1000)</div>
    </div>
</template>

<!-- Template voor submenu van Upgrade 2 -->
<template id="upgrade2-template">
    <div class="upgrade-popup">
        <div class="upgrade-option" data-power="1" data-cost="100">+1/sec (kost 100)</div>
        <div class="upgrade-option" data-power="5" data-cost="400">+5/sec (kost 400)</div>
        <div class="upgrade-option" data-power="20" data-cost="1500">+20/sec (kost 1500)</div>
    </div>
</template>

<!-- Template voor submenu van Upgrade 3 (Multiplier) -->
<template id="upgrade3-template">
    <div class="upgrade-popup">
        <div class="upgrade-option" data-multiplier="2" data-cost="500">x2 multiplier (kost 500)</div>
        <div class="upgrade-option" data-multiplier="5" data-cost="2000">x5 multiplier (kost 2000)</div>
        <div class="upgrade-option" data-multiplier="10" data-cost="10000">x10 multiplier (kost 10000)</div>
    </div>
</template>

<!-- Template voor submenu van Upgrade 4 (Time Booster) -->
<template id="upgrade4-template">
    <div class="upgrade-popup">
        <div class="upgrade-option" data-multiplier="2" data-cost="1000">30 sec ×2 boost (kost 1000)</div>
        <div class="upgrade-option" data-multiplier="3" data-cost="5000">30 sec ×3 boost (kost 5000)</div>
        <div class="upgrade-option" data-multiplier="5" data-cost="20000">30 sec ×5 boost (kost 20000)</div>
    </div>
</template>


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

/* Popup styling */
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

// -------------------------
// Upgrade 1 submenu
// -------------------------
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
                upgrade1.innerHTML = `Upgrade 1 (+1/klik)<br>Klikkracht: ${clickPower}`;
            } else {
                option.textContent = `Niet genoeg hotdogs! (kost ${cost})`;
                setTimeout(() => option.textContent = `+${power} per klik (kost ${cost})`, 1500);
            }
        });
    });
});

// -------------------------
// Upgrade 2 submenu
// -------------------------
upgrade2.addEventListener('click', () => {

    // Sluit popup 1 als die open is
    if (popup1) {
        popup1.remove();
        popup1 = null;
    }

    // Als popup 2 al open is → sluit hem
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
                upgrade2.innerHTML = `Upgrade 2 (+1/sec)<br>Auto-power: ${autoPower}`;
            } else {
                option.textContent = `Niet genoeg hotdogs! (kost ${cost})`;
                setTimeout(() => option.textContent = `+${power}/sec (kost ${cost})`, 1500);
            }
        });
    });
});

const upgrade3 = document.querySelectorAll('.upgrade-item')[2];
const template3 = document.querySelector('#upgrade3-template');


// -------------------------
// Upgrade 3 submenu
// -------------------------
upgrade3.addEventListener('click', () => {

    // Sluit andere popups
    if (popup1) { popup1.remove(); popup1 = null; }
    if (popup2) { popup2.remove(); popup2 = null; }

    // Als popup 3 al open is → sluit hem
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
                upgrade3.innerHTML = `Upgrade 3 (Multiplier)<br>x${clickMultiplier}`;
            } else {
                option.textContent = `Niet genoeg hotdogs! (kost ${cost})`;
                setTimeout(() => {
                    option.textContent = `x${mult} multiplier (kost ${cost})`;
                }, 1500);
            }
        });
    });
});

const upgrade4 = document.querySelectorAll('.upgrade-item')[3];
const template4 = document.querySelector('#upgrade4-template');

// Upgrade 4 — Time Booster submenu
upgrade4.addEventListener('click', () => {

    // Sluit andere popups
    if (popup1) { popup1.remove(); popup1 = null; }
    if (popup2) { popup2.remove(); popup2 = null; }
    if (popup3) { popup3.remove(); popup3 = null; }

    // Als popup 4 al open is → sluit hem
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
                upgrade4.innerHTML = `Upgrade 4 (Boost)<br>${boost}× actief`;

                // Boost eindigt na 30 seconden
                boostTimeout = setTimeout(() => {
                    activeBoost = 1;
                    upgrade4.innerHTML = `Upgrade 4 (Boost)<br>Geen boost`;
                }, 30000);

            } else {
                option.textContent = `Niet genoeg hotdogs! (kost ${cost})`;
                setTimeout(() => {
                    option.textContent = `30 sec ×${boost} boost (kost ${cost})`;
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

upgrade1.innerHTML = `Upgrade 1 (Cllik)<br>Click-Power: ${clickPower}`;
upgrade2.innerHTML = `Upgrade 2 (Auto)<br>Auto-Power: ${autoPower}`;
upgrade3.innerHTML = `Upgrade 3 (Multiplier)<br>Multiplier: x${clickMultiplier}`;
upgrade4.innerHTML = `Upgrade 4 (Boost)<br>Geen boost`;
</script>

<?php include 'includes/footer.php'; ?>