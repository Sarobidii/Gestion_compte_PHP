<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $sql = "SELECT id, nom, prenom FROM compte WHERE id != $id_user";
    $result = mysqli_query($conn, $sql);
    $sql1 = "SELECT id_compte_amis FROM amis WHERE id_compte = $id_user";
    $result1 = mysqli_query($conn, $sql1);
    $sql2 = "SELECT id_compte FROM amis WHERE id_compte_amis = $id_user";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result) > 0) {
        echo '<ul>';
        while ($row = mysqli_fetch_assoc($result)) {
            $id_ami = $row['id']; 
            $prenom = htmlspecialchars($row['prenom']);
            $nom = htmlspecialchars($row['nom']);
            
            echo '<li>';
            echo $prenom . ' ' . $nom;

            mysqli_data_seek($result1, 0);  

            $is_amis = false;  

            while ($row1 = mysqli_fetch_assoc($result1)) {
                if ($id_ami == $row1['id_compte_amis']) {
                    $is_amis = true;
                    break;
                }
            }
            while ($row2 = mysqli_fetch_assoc($result2)) {
                if ($id_ami == $row2['id_compte']) {
                    $is_amis = true;
                    break;
                }
            }

            if ($is_amis) {
                echo '<button>Amis</button>';
            } else {
                echo '<form action="ajout_amis.php" method="post">';
                echo '<input type="hidden" name="id_ami" value="'. $id_ami .'" >';
                echo '<button type="submit">Ajouter comme ami</button>';
                echo '</form>';
            }

            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<p>Aucun autre utilisateur trouvé.</p>';
    }

} else {
    header("Location: login_form.php");
    exit();
}
?>
