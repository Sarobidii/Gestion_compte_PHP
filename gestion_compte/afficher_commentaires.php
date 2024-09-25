<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

if (isset($_POST['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_POST['id_publication']);
} elseif (isset($_GET['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
} else {
    echo 'Invalid request. Publication ID not provided.';
    exit();
}

// Fetch the publication data
$sqlPublication = "
    SELECT p.id, p.contenu, c.nom, c.prenom, p.date_lance
    FROM publication p
    JOIN compte c ON p.id_compte = c.id
    WHERE p.id = $id_publication";

$resultPublication = mysqli_query($conn, $sqlPublication);

if (mysqli_num_rows($resultPublication) > 0) {
    $rowPublication = mysqli_fetch_assoc($resultPublication);

    // Display the publication
    echo '<div class="publication">';
    echo '<p>' . htmlspecialchars($rowPublication['prenom']) . ' ' . htmlspecialchars($rowPublication['nom']) . '</p>';
    echo '<small>' . htmlspecialchars($rowPublication['date_lance']) . '</small>';
    echo '<p>' . htmlspecialchars($rowPublication['contenu']) . '</p>';

    $id_publication = $rowPublication['id'];
    $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_publication WHERE id_publication = $id_publication";
    $resultCount = mysqli_query($conn, $sqlCount);
    $rowCount = mysqli_fetch_assoc($resultCount);
    $nombre_reactions = $rowCount['total_reactions'];

    $sqlCount1 = "SELECT COUNT(*) AS total_commentaires FROM commentaire WHERE id_publication = $id_publication";
    $resultCount1 = mysqli_query($conn, $sqlCount1);
    $rowCount1 = mysqli_fetch_assoc($resultCount1);
    $nombre_commentaires = $rowCount1['total_commentaires'];

    if ($nombre_reactions > 0) {
        echo '<form action="afficher_reactions.php" method="get">';
        echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
        echo '<br>';
        echo '<button type="submit">' . $nombre_reactions . ' réaction(s)' . '</button>';
        echo '</form>';
    } else {
        echo 'Aucune réaction';
    }

    if ($nombre_commentaires > 0) {
        echo '<form action="afficher_commentaires.php" method="get">';
        echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
        echo '<br>';
        echo '<button type="submit">' . $nombre_commentaires . ' commentaire(s)' . '</button>';
        echo '</form>';
    } else {
        echo 'Aucun commentaire';
    }

    echo '<div class="buttons">';
    echo '<form action="reaction.php" method="post">';
    echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';

    echo '<select name="reaction_' . htmlspecialchars($id_publication) . '" onchange="this.form.submit()">';
    echo '<option value="">Choisir une réaction</option>';

    $sql2 = "SELECT id, type_reaction FROM reaction";
    $result2 = mysqli_query($conn, $sql2);
    if (mysqli_num_rows($result2) > 0) {
        while ($row2 = mysqli_fetch_assoc($result2)) {
            echo "<option value='" . htmlspecialchars($row2['id']) . "'>" . htmlspecialchars($row2['type_reaction']) . "</option>";
        }
    }

    echo '</select><br><br>';
    echo '</form>';
    echo '<form action="commentaire.php" method="post">';
    echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
    echo '<textarea name="contenu" placeholder="Ajouter un commentaire..." cols="30" rows="3"></textarea>';
    echo '<br>';
    echo '<button type="submit">Envoyer</button>';
    echo '</form>';
    echo '</div>';
    echo '</div>'; // End of publication div

    // Now fetch and display comments
    $sql = "
        SELECT rp.date_lance, rp.id, rp.id_compte, rp.contenu, c.nom, c.prenom
        FROM commentaire rp
        JOIN compte c ON rp.id_compte = c.id
        WHERE rp.id_publication = $id_publication";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="commentaire">';
            echo $row['prenom'] . ' ' . $row['nom'] . '<br>';
            echo "<p> " . htmlspecialchars($row['date_lance']) . "</p>";
            echo '<div>' . htmlspecialchars($row['contenu']) . '</div><br>';
            $id_commentaire = $row['id'];

            // Count reactions for each comment
            $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_commentaire WHERE id_commentaire = $id_commentaire";
            $resultCount = mysqli_query($conn, $sqlCount);
            $rowCount = mysqli_fetch_assoc($resultCount);
            $nombre_reactions = $rowCount['total_reactions'];

            if ($nombre_reactions > 0) {
                echo '<form action="afficher_reactions_commentaire.php" method="post">';
                echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
                echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                echo '<br>';
                echo '<button type="submit">' . $nombre_reactions . ' réaction(s)' . '</button>';
                echo '</form>';
            } else {
                echo 'Aucune réaction';
            }

            // Reactions to comments
            echo '<form action="reaction_commentaire.php" method="post">';
            echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';

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
            
            $sqlCheckResponses = "SELECT COUNT(*) AS total_responses FROM reponse_commentaire WHERE id_commentaire = $id_commentaire";
            $resultCheckResponses = mysqli_query($conn, $sqlCheckResponses);
            $rowCheckResponses = mysqli_fetch_assoc($resultCheckResponses);
            $nombre_reponses = $rowCheckResponses['total_responses'];

            if ($nombre_reponses > 0) {
                echo '<form action="voir_reponses.php" method="get">';
                $id_compte = $row['id_compte'];
                echo '<input type="hidden" name="id_compte" value="' . htmlspecialchars($id_compte) . '">';
                echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
                echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                echo '<button type="submit">Voir réponse(' . $nombre_reponses . ')</button>';
                echo '</form>';
            }

            echo '<form action="reponse_commentaire.php" method="post">';
            echo '<input type="hidden" name="fichier" value="afficher_commentaires.php?id_publication=' . htmlspecialchars($id_commentaire) . '">';
            echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
            echo '<textarea name="contenu" placeholder="Ajouter une réponse..." cols="30" rows="3"></textarea>';
            echo '<br>';
            echo '<button type="submit">Répondre</button>';
            echo '</form>';
            echo '</div>'; // End of commentaire div
        }
    } else {
        echo 'No comments found for this publication.';
    }
} else {
    echo 'No publication found with this ID.';
}
?>
