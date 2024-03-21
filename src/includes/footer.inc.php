<footer>

        <div id="footer-logo-container">
    <a href="<?php echo $_SERVER["PHP_SELF"] ?>">
    <img src="public/imagen.png" id="logo-footer"></img></a>
        <h1 id="footer-text-title">YouCabulary</h1>
    </div>


    <p id="footer-last-line"><a href="mailto: andresdrimer@hotmail.com">Andrés Drimer @ <?php echo Date('Y'); ?></a></p>
</footer>

<?php 
    $bg_color_standard = isset($_SESSION["bg_color"]) ? $_SESSION["bg_color"] : "#ffdf66";
    ?>
    <style>
 footer {
            background-color: <?php echo $bg_color_standard; ?>;
        }

    </style>





<script src="src/resources/js/script.js"></script>
<script src='src/resources/js/register_login.js'></script>

</body>
</html>