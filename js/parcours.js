// Fonction pour afficher les coordonnées
function afficherCoordonnees(points) {
    let coordonneesDiv = document.getElementById('coordonnees');
    coordonneesDiv.innerHTML = '';
    points.forEach(function (point, index) {
        let latitude = point[0];
        let longitude = point[1];
        let lettre = String.fromCharCode(65 + index % 26);
        coordonneesDiv.innerHTML += '<p><b>Point ' + lettre + ' :</p>';
        coordonneesDiv.innerHTML += '<p>Lat,Long : ' + latitude + ', <br>' + longitude + '</p><br>';

    });
}

// Fonction principale pour initialiser la carte et gérer les clics
function initMap() {
    const latitude = 48.882978625707224; 
    const longitude = 2.6123552458777604; 

    var map = L.map('map').setView([latitude, longitude], 18);
    var marker = L.marker([latitude, longitude]).addTo(map);
    marker.bindPopup('Votre drone est ici').openPopup();

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);


    var points = [[latitude, longitude]];

    function onMapClick(e) {

        const maxClicks = 5;

        if (points.length >= maxClicks) {
            map.off('click', onMapClick);
            return;
        }

        let latlng = e.latlng;

        points.push([latlng.lat, latlng.lng]);

        if (points.length > 1) {
            if (typeof polyline !== 'undefined') {
                map.removeLayer(polyline);
            }
            polyline = L.polyline(points, { color: 'red' }).addTo(map);
            btnCreerSurface.disabled = false;
        }
        marker = L.marker(latlng).addTo(map);

        afficherCoordonnees(points);
    }
    map.on('click', onMapClick); // Activer les clics sur la carte


    const btnCreerSurface = document.getElementById('creerZone');
    const btnEnvoyerDonnees = document.getElementById('envoyerDonnees');
    const btnSupprimerPoint = document.getElementById('supprimerPoint');
    let zonePolygon;


    // Créer la zone de topographie
    function creerSurface() 
    {
        if (points.length > 1) {
            zonePolygon = L.polygon(points).addTo(map);
            zonePolygon.bindPopup("Surface topographique");
            if (zonePolygon) {
                btnCreerSurface.disabled = true;
                btnEnvoyerDonnees.disabled = false;
                map.off('click', onMapClick); // Désactiver les clics sur la carte
            } 
        } else {
            document.getElementById('error').style.display = 'block';
            document.getElementById('success').style.display = 'none';
        }
    }


    // Supprimer la zone de topographie
    function supprimerPoint() 
    {
        btnEnvoyerDonnees.disabled = true;
        btnCreerSurface.disabled = false;

        if (points.length > 1) {
            map.on('click', onMapClick);
            points = [points[0]];
            map.removeLayer(polyline);

            // Supprimer tous les marqueurs existants sauf le premier
            map.eachLayer(function (layer) {
                if (layer instanceof L.Marker && !layer.getLatLng().equals(L.latLng(points[0]))) {
                    map.removeLayer(layer);
                }
            });

            // Supprimer la zone topographique
            if (zonePolygon) {
                map.removeLayer(zonePolygon);
                zonePolygon = null;
            }
            afficherCoordonnees(points);
            document.getElementById('success').style.display = 'none';
        }
    }


    // Envoyer les données 
    function envoyerDonnees() 
    {
        let nomParcours = prompt("Donnez un nom à votre parcours :");
        let success = document.getElementById('success');
        let error = document.getElementById('error');
    
        if (nomParcours !== null) {

            // Récupérer les coordonnées de chaque point
            var coordonnees = {
                nomParcours: nomParcours,
                points: JSON.stringify(points)
            };
    
            // Envoyer les coordonnées à un script PHP via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/save_data.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            let response = JSON.parse(xhr.responseText);
                            if (response.status === 'success') {
                                success.style.display = 'block';
                                success.innerText = response.message;
                                error.style.display = 'none';
                            } else {
                                error.style.display = 'block';
                                error.innerText = response.message;
                            }
                        } catch (e) {
                            console.error('Invalid JSON response:', e);
                            error.style.display = 'block';
                            error.innerText = 'Une erreur s\'est produite lors du traitement de la réponse. Veuillez réessayer.';
                        }
                    } else {
                        console.error('Request failed with status:', xhr.status);
                        error.style.display = 'block';
                        error.innerText = 'Impossible d\'envoyer la requête au serveur. Veuillez réessayer.';
                    }
                }
            };
            xhr.send(JSON.stringify(coordonnees));
        }
    }
    
    
    btnCreerSurface.addEventListener('click', creerSurface);
    btnSupprimerPoint.addEventListener('click', supprimerPoint);
    btnEnvoyerDonnees.addEventListener('click', envoyerDonnees);
}