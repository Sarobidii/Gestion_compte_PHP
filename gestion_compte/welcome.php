<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button><a href="publication.php">Créer une publication</a></button>
    <button><a href="suggestion.php">Voir les suggestions d'amis</a></button>
<?php
session_start();
include 'db_connect.php';

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $sql_amis = "SELECT id_compte_amis FROM amis WHERE id_compte = $id_user";
    $result_amis = mysqli_query($conn, $sql_amis);
    
    $amis_ids = [$id_user]; 

    if (mysqli_num_rows($result_amis) > 0) {
        while ($row_amis = mysqli_fetch_assoc($result_amis)) {
            $amis_ids[] = $row_amis['id_compte_amis'];
        }
    }

    $sql_amis1 = "SELECT id_compte FROM amis WHERE id_compte_amis = $id_user";
    $result_amis1 = mysqli_query($conn, $sql_amis1);

    if (mysqli_num_rows($result_amis1) > 0) {
        while ($row_amis1 = mysqli_fetch_assoc($result_amis1)) {
            $amis_ids[] = $row_amis1['id_compte'];
        }
    }

    $amis_ids_str = implode(',', $amis_ids); 
    $sql = "SELECT p.id, p.contenu, p.date_lance, c.nom, c.prenom 
            FROM publication p 
            JOIN compte c ON p.id_compte = c.id 
            WHERE p.id_compte IN ($amis_ids_str) 
            ORDER BY p.date_lance DESC"; 

    $result = mysqli_query($conn, $sql);

    $sql2 = "SELECT id, type_reaction FROM reaction";
    $result2 = mysqli_query($conn, $sql2);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="publication">';
            echo '<p>' . htmlspecialchars($row['prenom']) . ' ' . htmlspecialchars($row['nom']) . '</p>';
            echo '<small>' . htmlspecialchars($row['date_lance']) . '</small>';
            echo '<p>' . htmlspecialchars($row['contenu']) . '</p>';

            $id_publication = $row['id'];
            $sqlCount = "SELECT COUNT(*) AS total_reactions FROM reaction_publication WHERE id_publication = $id_publication";
            $resultCount = mysqli_query($conn, $sqlCount);
            $rowCount = mysqli_fetch_assoc($resultCount);
            $nombre_reactions = $rowCount['total_reactions'];

            $sqlCount1 = "SELECT COUNT(*) AS total_commentaires FROM commentaire WHERE id_publication = $id_publication";
            $resultCount1 = mysqli_query($conn, $sqlCount1);
            $rowCount1 = mysqli_fetch_assoc($resultCount1);
            $nombre_commentaires = $rowCount1['total_commentaires'];

            if ($nombre_reactions > 0) {
                echo '<form action="afficher_reactions.php" method="post">';
                echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                echo '<br>';
                echo '<button type="submit">' . $nombre_reactions . ' réaction(s)' .'</button>';
                echo '</form>';
            } else {
                echo 'Aucune réaction';
            }
            if ($nombre_commentaires > 0) {
                echo '<form action="afficher_commentaires.php" method="post">';
                echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';
                echo '<br>';
                echo '<button type="submit">' . $nombre_commentaires . ' commentaire(s)' .'</button>';
                echo '</form>';
            } else {
                echo 'Aucune commentaire';
            }

            echo '<div class="buttons">';
            echo '<form action="reaction.php" method="post">';
            echo '<input type="hidden" name="id_publication" value="' . htmlspecialchars($id_publication) . '">';

            echo '<select name="reaction_' . htmlspecialchars($id_publication) . '" onchange="this.form.submit()">';
            echo '<option value="">Choisir une réaction</option>';

            if (mysqli_num_rows($result2) > 0) {
                mysqli_data_seek($result2, 0);
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
            echo '</div>';
        }
    } else {
        echo '<p>Aucune publication trouvée.</p>';
    }

} else {
    header("Location: login_form.php");
}
?>
</body>
</html>