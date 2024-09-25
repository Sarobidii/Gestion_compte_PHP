<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue</title>
</head>
<body>
    <h1>Bienvenue, <?php echo $_SESSION['email_user']; ?></h1>
    
    <form action="" method="post">
        <textarea name="contenu" placeholder="Écrire quelque chose..." cols="30" rows="10"></textarea>
        <br>
        <button type="submit">Publier</button>
    </form>

    <?php
    if (isset($_POST['contenu'])) {
        $contenu = $_POST['contenu'];
        $id_compte = $_SESSION['id_user']; 
        
        $contenu = mysqli_real_escape_string($conn, $contenu);

        $sql = "INSERT INTO publication (contenu, id_compte, date_lance) VALUES ('$contenu', $id_compte, NOW())";

        if (mysqli_query($conn, $sql)) {
            header("Location: welcome.php");
            exit();
        } else {
            echo "Erreur : " . mysqli_error($conn);
        }
    }
    ?>
</body>
</html>
