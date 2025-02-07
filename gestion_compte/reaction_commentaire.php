<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    if (isset($_POST['id_publication']) && isset($_POST['id_commentaire']) && isset($_POST['reaction_' . $_POST['id_commentaire']])) {
        $id_commentaire = $_POST['id_commentaire'];
        $id_reaction = $_POST['reaction_' . $id_commentaire];
        $id_publication = $_POST['id_publication'];

        if (!empty($id_reaction)) {
            $sql_verification = "SELECT id_reaction FROM reaction_commentaire WHERE id_commentaire = $id_commentaire AND id_compte = $id_user";
            $result_verification = mysqli_query($conn, $sql_verification);

            if (mysqli_num_rows($result_verification) == 1) {
                $row = mysqli_fetch_assoc($result_verification);
                $id_reaction_existante = $row['id_reaction'];

                $sql_update = "UPDATE reaction_commentaire SET id_reaction = $id_reaction WHERE id_commentaire = $id_commentaire AND id_compte = $id_user";
                if (mysqli_query($conn, $sql_update)) {
                    header("Location: afficher_commentaires.php?id_publication=$id_publication");
                    exit();
                } else {
                    echo '<p>Erreur lors de la mise à jour de la réaction.</p>';
                }
            } else {
                $sql_insert = "INSERT INTO reaction_commentaire (id_commentaire, id_compte, id_reaction) 
                               VALUES ($id_commentaire, $id_user, $id_reaction)";
                if (mysqli_query($conn, $sql_insert)) {
                    header("Location: afficher_commentaires.php?id_publication=$id_publication");
                    exit();
                } else {
                    echo '<p>Erreur lors de l\'enregistrement de la réaction.</p>';
                }
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
