<html>

    <head>
        <title>Art Owner Portal Page</title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body> <div class="container">
        <div class="menu">
            <a href="index.php" class="active">Gallery & Account Management</a>
            <a href="art_index.php" class="active">Art Owner Portal</a>
        </div>
        <div class="sub-header">
            Art Owner Portal
        </div>

        <h2 >Login</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="loginRequest" name="loginRequest">
                Email: <input type="text" name="loginEmail"> <br /><br />
                <p><input type="submit" value="Login" name="login"></p>
            </form>
            <div class="filler"></div>

        <h2>View My Artwork in the Gallery</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="seeMyArtRequest" name="seeMyArtRequest">
                <p>Choose Medium:</p>
                <input type="radio" id="all" name="select_art_type" value="All">
                <label for="all">All</label><br>
                <input type="radio" id="paintings" name="select_art_type" value="Paintings">
                <label for="paintings">Paintings</label><br>
                <input type="radio" id="sculptures" name="select_art_type" value="Sculptures">
                <label for="sculptures">Sculptures</label>
                <p>Choose Attributes of Art to Display</p>
                <input type="checkbox" id="year" name="attributeYear" value=", a.YearCreated">
                <label for="year"> Year</label><br>
                <input type="checkbox" id="price" name="attributePrice" value=", a.Price">
                <label for="price"> Price</label><br>
                <p>Where meets criteria</p>


                <select name="where_attribute" id="where_attribute">
                    <option value="">--- Choose an attribute ---</option>
                    <option value="YearCreated">Year</option>
                    <option value="price">Price</option>
                </select>

                <select name="where_operation" id="where_operation">
                    <option value="">--- Choose an operation ---</option>
                    <option value=">">></option>
                    <option value="<"><</option>
                    <option value="=">=</option>
                </select>

                <input type="number" id="where_value" name="where_value">

                <p><input type="submit" value="View" name="view"></p>
            </form>
            <div class="filler"></div>

        <h2>My Exhibition Fees</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="seeMyFeesRequest" name="seeMyFeesRequest">
                <p><input type="submit" value="Sum Fees" name="sum_fees"></p>
            </form>
        <div class="filler"></div>

        <h2>Logout</h2>
            <form method="GET" action="wrapper.php">
                <input type="hidden" id="logoutRequest" name="logoutRequest">
                <p><input type="submit" value="Logout" name="logout"></p>
            </form>
        <div class="filler"></div>

        <?php
            //echo"hello";
            require_once('owner_portal.php');

        ?>
    </div>
    </body>
</html>
