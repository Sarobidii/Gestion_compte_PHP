<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['id_publication'])) {
   $id_publication = mysqli_real_escape_string($conn, $_POST['id_publication']);
    
    $sql = "
        SELECT rp.id, rp.id_compte, rp.contenu, c.nom, c.prenom
        FROM commentaire rp
        JOIN compte c ON rp.id_compte = c.id
        WHERE rp.id_publication = $id_publication";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo $row['prenom'] . ' ' . $row['nom'] . '<br>';
            echo '<div>' . $row['contenu'] . '</div><br>';
            $id_commentaire = $row['id'];

            $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_commentaire WHERE id_commentaire = $id_commentaire";
            $resultCount = mysqli_query($conn, $sqlCount);
            $rowCount = mysqli_fetch_assoc($resultCount);
            $nombre_reactions = $rowCount['total_reactions'];

            if ($nombre_reactions > 0) {
                echo '<form action="afficher_reactions_commentaire.php" method="post">';
                echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
                echo '<br>';
                echo '<button type="submit">' . $nombre_reactions . ' réaction(s)' .'</button>';
                echo '</form>';
            } else {
                echo 'Aucune réaction';
            }
            
            $sql2 = "SELECT id, type_reaction FROM reaction";
            $result2 = mysqli_query($conn, $sql2);
            echo '<form action="reaction_commentaire.php" method="post">';
            echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';

            echo '<select name="reaction_' . htmlspecialchars($id_commentaire) . '" onchange="this.form.submit()">';
            echo '<option value="">Choisir une réaction</option>';

            if (mysqli_num_rows($result2) > 0) {
                mysqli_data_seek($result2, 0);
                while ($row2 = mysqli_fetch_assoc($result2)) {
                    echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</option>";
                }
            }

            echo '</select><br><br>';
            echo '</form>';

        }
    } else {
        echo 'No comments found for this publication.';
    } 
} else {
    echo 'Invalid request. Publication ID not provided.';
}
?>
