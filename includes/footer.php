</div>

<footer class="footer">

    <div class="footer-container">

        <div class="footer-logo">
            <h2>🤖LankaGoAI</h2>
            <p>AI Powered Smart Travel Planner for Sri Lanka.</p>
        </div>

        <div class="footer-links">
            <h3>Quick Links</h3>

            <a href="index.php">Home</a>
            <a href="planner.php">Planner</a>
            <a href="weather.php">Weather</a>
            <a href="chatbot.php">AI Chat</a>
        </div>

        <div class="footer-socials">
            <h3>Connect</h3>

            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
            <a href="#"><i class="fab fa-tiktok"></i></a>

        </div>

    </div>

    <div class="footer-bottom">
    © <?php echo date("Y"); ?> LankaGoAI. All Rights Reserved.
    <br>
    Powered by
    <a href="https://sathirasugeesvara.github.io" target="_blank" class="xenora-link">
        Sathira Sugeesvara
    </a>
</div>

</footer>

<script>

window.addEventListener(
"load",

()=>{

    const loader =
    document.getElementById(
        "loader"
    );

    loader.style.opacity = "0";

    setTimeout(()=>{

        loader.style.display =
        "none";

    },500);
});

</script>

</body>
</html>