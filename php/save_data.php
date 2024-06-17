<?php
include 'db_config.php';

try {

    $data = json_decode(file_get_contents("php://input"), true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input');
    }

    // Commencer une transaction
    $db->beginTransaction();


    $stmt_verif = $db->prepare("SELECT id FROM drone.parcours WHERE nom = (:nom)");
    $stmt_verif->bindParam(':nom', $data['nomParcours']);
    $stmt_verif->execute();


    // Vérifier si le nom du parcours existe 
    if ($stmt_verif->rowCount() > 0) {
        throw new Exception('Un parcours avec ce nom existe déjà. Veuillez choisir un autre nom.');
    }


    // Insérer le nom du parcours dans la BDD
    $stmt_parcours = $db->prepare("INSERT INTO drone.parcours (nom) VALUES (:nom) RETURNING id");
    $stmt_parcours->bindParam(':nom', $data['nomParcours']);
    $stmt_parcours->execute();
    $parcours_id = $stmt_parcours->fetchColumn();


    $stmt_points = $db->prepare("INSERT INTO drone.coordonnee (parcours_id, point, latitude, longitude) VALUES (:parcours_id, :point, :latitude, :longitude)");

    if ($stmt_points) {

        $tab_points = json_decode($data['points'], true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Invalid JSON points');
        }

        $point = 'A';

        // Parcourir le tableau
        foreach ($tab_points as $coord) {
            
            // Convertir en float
            $latitude = floatval($coord[0]);
            $longitude = floatval($coord[1]);

            $stmt_points->bindParam(':parcours_id', $parcours_id);
            $stmt_points->bindParam(':point', $point);
            $stmt_points->bindParam(':latitude', $latitude);
            $stmt_points->bindParam(':longitude', $longitude);
            $stmt_points->execute();

            $point = chr(ord($point) + 1);
        }

        // Valider la transaction
        $db->commit();

        $response = array();
        $nomParcours = $data['nomParcours'];

        $response['status'] = 'success';
        $response['message'] = "Le parcours \"$nomParcours\" a été enregistré avec succès";

    } else {
        throw new Exception('Erreur lors de l\'enregistrement du parcours');
    }

} catch (Exception $e) {

    // Annuler la transaction en cas d'erreur
    $db->rollBack();

    // Indiquer l'erreur
    $response['status'] = 'error';
    $response['message'] = $e->getMessage();
}

// Convertit le tableau de réponse en JSON et l'affiche
header('Content-Type: application/json');
echo json_encode($response);
