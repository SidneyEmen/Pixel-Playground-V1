<footer>
    <img src="img/logo_gameverse.png" alt="GameVerse Logo">

    <a>Student example website - 1st year, last project</a>
    <a>Main purpose: Vulnerabilities, CRUD, Game logic</a>

    <button id="darkmode-btn">Dark Mode</button>
    
</footer>
</body>

<style>
    .darkmode {
        background-color: var(--background_lightmode);
        color: var(--white);
    }

    .darkmode > header,
    .darkmode > footer {
        background-color: var(--header_navbar_lightmode);
    }

    .darkmode > a {
        color: var(--white);
    }

</style>
<script>
const btn = document.getElementById('darkmode-btn');

// Darkmode Saved
if (localStorage.getItem('darkmode') === 'enabled') {
    document.body.classList.add('darkmode');
}

btn.addEventListener('click', () => {
    document.body.classList.toggle('darkmode');

    if (document.body.classList.contains('darkmode')) {
        localStorage.setItem('darkmode', 'enabled');
    } else {
        localStorage.setItem('darkmode', 'disabled');
    }
});

</script>
</html>