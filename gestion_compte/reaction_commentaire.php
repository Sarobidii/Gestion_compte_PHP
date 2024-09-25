<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    if (isset($_POST['id_commentaire']) && isset($_POST['reaction_' . $_POST['id_commentaire']])) {
        $id_commentaire = $_POST['id_commentaire'];
        $id_reaction = $_POST['reaction_' . $id_commentaire];

        if (!empty($id_reaction)) {
            $sql = "SELECT type_reaction FROM reaction WHERE id = $id_reaction";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);
                $type_reaction = $row['type_reaction'];
                
                $sql_insert = "INSERT INTO reaction_commentaire (id_commentaire, id_compte, id_reaction) 
                               VALUES ($id_commentaire, $id_user, $id_reaction)";
                if (mysqli_query($conn, $sql_insert)) {
                    header("Location: afficher_commentaires.php");
                } else {
                    echo '<p>Erreur lors de l\'enregistrement de la réaction.</p>';
                }
            } else {
                echo '<p>Réaction non trouvée.</p>';
            }
        } else {
            echo '<p>Aucune réaction sélectionnée.</p>';
        }
    } else {
        echo '<p>Données non valides.</p>';
    }
} else {
    echo '<p>Veuillez vous connecter pour réagir aux publications.</p>';
}
?>
