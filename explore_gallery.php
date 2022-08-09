<html>

    <head>
        <title>Art in the Gallery</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
            <div class="vertical-menu">
                <a href="index.php" class="active">Account Management</a>
                <a href="art_index.php">Art Owner Portal</a>
                <a href="explore_gallery.php">Explore the Gallery</a>
            </div>
        
    <h2>Display Art by Artist</h2>
            <form method="GET" action="explore_gallery.php">
                <!-- if you want another page to load after the button is clicked, you have to specify that page in the action parameter -->
                <input type="hidden" id="displayArtbyArtist" name="displayArtbyArtist">
                <p><input type="submit" value="Display" name="displayArtbyArtist"></p>
            </form>
    </body>
</html> 
