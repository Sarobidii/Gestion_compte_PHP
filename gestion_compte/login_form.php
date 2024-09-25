<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Formulaire de connexion</h2>
    <form action="login.php" method="post">
        <?php
            session_start();
            if(isset($_SESSION["error"]))
            {
                echo "<p class='error'>" . $_SESSION["error"] . "</p>";
            }
            echo '<label for="email">E-mail : </label>';
            if(isset($_SESSION["email"]))
            {
                echo "<input type='email' name='email' value='" . $_SESSION["email"] . "' required><br><br>";
            }
            else
            {
                echo "<input type='email' name='email' required><br><br>";
            }
            echo '<label for="mdp">Mot de passe : </label>';
            if(isset($_SESSION["mdp"]))
            {
                echo "<input type='password' name='mdp' value='" . $_SESSION["mdp"] . "' required><br><br>";
            }
            else
            {
                echo "<input type='password' name='mdp' required><br><br>";
            }
        ?>
        <input type="submit" value="Connexion"><br><br>
        <a href="recuperation.php">Mot de passe oublié</a>
    </form>
</body>
</html>
