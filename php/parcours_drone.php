<?php

include 'db_config.php';

// Récuperer la dernière position du drone
$stmt = $db->query("SELECT latitude, longitude FROM drone.position ORDER BY id_position DESC LIMIT 1;");
$result = $stmt->fetch();
$latitude = $result['latitude'];
$longitude = $result['longitude'];
$adresse = "32 Av. de l'Europe, 77500 Chelles";

?>

<style>
    .div-map {
        padding-left: 3rem;
        padding-right: 3rem;
    }

    #map {
        height: 70vh;
        width: 90vh;
        border: 3px solid black;
        box-shadow: 10px 10px 15px rgba(105, 105, 105, 0.3),  -10px -10px 15px rgba(105, 105, 105, 0.3);
    }
</style>

<div class="back">
    <div class="contenu-page-parcours">

        <div class="latlng">
            <div style="display: flex;">
                <h4>Position du drone</h4>
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16" style="margin-left: 0.5rem;">
                    <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10" />
                    <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                </svg>
            </div>
            <br>
            <label class="latitudeLabel" for="latitudeInput"><b>Adresse</b></label>
            <input type="text" id="latitudeInput" value="<?php echo $adresse ?>">
            <br><br>
            <label class="latitudeLabel" for="latitudeInput"><b>Latitude</b></label>
            <input type="text" id="latitudeInput" value="<?php echo $latitude ?>">
            <br><br>
            <label class="longitudeLabel" for="longitudeInput"><b>Longitude</b></label>
            <input type="text" id="longitudeInput" value="<?php echo $longitude ?>">
            <br><br>
            <label class="altitudeLabel" for="altitudeLabel"><b>Altitude</b></label>
            <input type="text" id="altitudeInput" value="0 m">
            <br><br>
            <div style="border: 1px solid rgb(232, 232, 232);"></div>
            <?php include 'html/flying_drone.html'; ?>
        </div>

        <script src="leaflet/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <div class="div-map">
            <div id="map"></div>
            <div style="display: flex; justify-content: space-between;">
                <input type="button" class="btn btn-outline-danger" style="margin-top: 1rem;" ; id="supprimerPoint" value="Effacer les points">
                <input type="button" class="btn btn-outline-primary" style="margin-top: 1rem;" ; id="creerZone" value="Créer une surface">
                <input type="button" class="btn btn-outline-success" style="margin-top: 1rem;" ; id="envoyerDonnees" value="Enregistrer et envoyer le parcours" disabled>
            </div>
        </div>

        <div class="latlng">
            <div style="display: flex; ">
                <h4>Point ajouté sur la carte</h4>

                <br><br>
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-pin-map-fill" viewBox="0 0 16 16" style="margin-left: 1rem;">
                    <path fill-rule="evenodd" d="M3.1 11.2a.5.5 0 0 1 .4-.2H6a.5.5 0 0 1 0 1H3.75L1.5 15h13l-2.25-3H10a.5.5 0 0 1 0-1h2.5a.5.5 0 0 1 .4.2l3 4a.5.5 0 0 1-.4.8H.5a.5.5 0 0 1-.4-.8z" />
                    <path fill-rule="evenodd" d="M4 4a4 4 0 1 1 4.5 3.969V13.5a.5.5 0 0 1-1 0V7.97A4 4 0 0 1 4 3.999z" />
                </svg>
            </div>
            <p>Vous pouvez ajouter jusqu'à <b>cinq</b> points <br> pour créer un parcours.</p>
            <br>
            <div class="points-latlng" id="coordonnees"></div>
        </div>

        <script src="js/parcours.js"></script>
        <script>
            initMap();
        </script>

    </div><br>

    <div id="error" class="alert alert-danger" role="alert" style="display: none;">
        Veuillez placer des points sur la carte avant de créer une zone de topographie
    </div>
    <div id="success" class="alert alert-success" role="alert" style="display: none;">
        Votre parcours a été enregistré avec succès
    </div>
    <div id="info" class="alert alert-info" role="alert" style="display: none;">
        Les points ont été supprimés de la carte
    </div>
</div>