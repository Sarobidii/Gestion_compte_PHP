<?php
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['id_user'])) {
    header("Location: login_form.php");
    exit();
}

// Check if required parameters are set via GET
if (isset($_GET['id_commentaire']) && isset($_GET['id_compte']) && isset($_GET['id_publication'])) {
    $id_publication = mysqli_real_escape_string($conn, $_GET['id_publication']);
    $id_commentaire = mysqli_real_escape_string($conn, $_GET['id_commentaire']);
    $id_compte = mysqli_real_escape_string($conn, $_GET['id_compte']);
} else {
    echo 'Invalid request. Publication ID not provided.';
    exit();
}

// Fetch the comment based on id_commentaire
$comment_query = "SELECT * FROM commentaire WHERE id = '$id_commentaire'";
$comment_result = mysqli_query($conn, $comment_query);

if (mysqli_num_rows($comment_result) > 0) {
    $comment = mysqli_fetch_assoc($comment_result);
    
    // Fetch user information
    $sql_compte = "SELECT nom, prenom FROM compte WHERE id = '$id_compte'";
    $compte_result = mysqli_query($conn, $sql_compte);
    
    if (!$compte_result) {
        echo "Database query failed: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($compte_result) > 0) {
        $row = mysqli_fetch_assoc($compte_result);
        echo htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '<br>';
    } else {
        echo "User not found.";
    }
    echo "<p>Date: " . htmlspecialchars($comment['date_lance']) . "</p>";

    echo "<p>" . htmlspecialchars($comment['contenu']) . "</p>";

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

    $sql2 = "SELECT id, type_reaction FROM reaction";
    $result2 = mysqli_query($conn, $sql2);
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
    echo '<form action="reponse_commentaire.php" method="post">';
    echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
    echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
    echo '<textarea name="contenu" placeholder="Ajouter une réponse..." cols="30" rows="3"></textarea>';
    echo '<br>';
    echo '<button type="submit">Répondre</button>';
    echo '</form>'; 





    
    // Fetch responses corresponding to the comment
    $response_query = "SELECT * FROM reponse_commentaire WHERE id_commentaire = '$id_commentaire'";
    $response_result = mysqli_query($conn, $response_query);

    if (mysqli_num_rows($response_result) > 0) {
        while ($response = mysqli_fetch_assoc($response_result)) {
            $id_compte1 = $response['id_compte'];
            $sql_compte1 = "SELECT nom, prenom FROM compte WHERE id = '$id_compte1'";
        $compte_result1 = mysqli_query($conn, $sql_compte1);
    
    if (!$compte_result1) {
        echo "Database query failed: " . mysqli_error($conn);
        exit();
    }

    if (mysqli_num_rows($compte_result1) > 0) {
        $row1 = mysqli_fetch_assoc($compte_result1);
        echo htmlspecialchars($row1['prenom']) . ' ' . htmlspecialchars($row1['nom']) . '<br>';
    } else {
        echo "User not found.";
    }
    echo "<p>" . htmlspecialchars($response['date_lance']) . "</p>";
            echo "<p>" . htmlspecialchars($response['contenu']) . "</p>";

$id_reponse_commentaire = $response['id'];


            $sqlCount1 = "SELECT COUNT(*) AS total_reactions FROM reaction_reponse_commentaire WHERE id_reponse_commentaire = $id_reponse_commentaire";
            $resultCount1 = mysqli_query($conn, $sqlCount1);
            $rowCount1 = mysqli_fetch_assoc($resultCount1);
            $nombre_reactions1 = $rowCount1['total_reactions'];
        
            if ($nombre_reactions1 > 0) {
                echo '<form action="afficher_reactions_reponse_commentaire.php" method="get">';
                echo '<input type="hidden" name="id_reponse_commentaire" value="' . htmlspecialchars($id_reponse_commentaire) . '">';
                echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                echo '<br>';
                echo '<button type="submit">' . $nombre_reactions . ' réaction(s)' . '</button>';
                echo '</form>';
            } else {
                echo 'Aucune réaction';
            }
        
            $sql2 = "SELECT id, type_reaction FROM reaction";
            $result2 = mysqli_query($conn, $sql2);
            // Reactions to comments
            echo '<form action="reaction_reponse_commentaire.php" method="post">';
            echo '<input type="hidden" name="fichier" value="voir_reponses.php?id_compte=' . htmlspecialchars($id_compte) . '&id_commentaire=' . htmlspecialchars($id_commentaire) . '&id_publication=' . htmlspecialchars($id_publication) . '">';
            echo '<input type="hidden" name="id_reponse_commentaire" value="' . htmlspecialchars($id_reponse_commentaire) . '">';
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
            echo '<form action="reponse_commentaire.php" method="post">';
            echo '<input type="hidden" name="fichier" value="voir_reponses.php?id_compte=' . htmlspecialchars($id_compte) . '&id_commentaire=' . htmlspecialchars($id_commentaire) . '&id_publication=' . htmlspecialchars($id_publication) . '">';
            echo '<input type="hidden" name="id_commentaire" value="' . htmlspecialchars($id_commentaire) . '">';
            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
            echo '<textarea name="contenu" placeholder="Ajouter une réponse..." cols="30" rows="3"></textarea>';
            echo '<br>';
            echo '<button type="submit">Répondre</button>';
            echo '</form>'; 





        }
    } else {
        echo "<p>No responses for this comment.</p>";
    }
} else {
    echo "Comment not found.";
}

// Close the database connection
mysqli_close($conn);
?>
