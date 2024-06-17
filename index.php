<?php 
include_once 'php/db_config.php'; 
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSS files -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/headers.css" rel="stylesheet">
    <link href="css/sign-in.css" rel="stylesheet">
    <link href="css/carousel.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/flying_drone.css" rel="stylesheet">
    <link href="leaflet/leaflet.css" rel="stylesheet" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="js/jquery-3.6.0.min.js"></script>
    <!-- Titre -->
    <title>Projet Drone</title>
</head>

<body>
    <?php
    if (isset($db) && $db == true) {

        // Inclure le header
        include 'html/header.html';

        // Inclure le carrousel si on est sur la page d'accueil
        if ($_SERVER['REQUEST_URI'] == '/drone/' || $_SERVER['REQUEST_URI'] == '/index.php') {
            include 'html/carrousel.html';
        }

        // Inclure le formulaire de login ou la carte selon la valeur de 'p' dans l'URL
        if (isset($_GET['p'])) {
            if ($_GET['p'] == 'login') {
                include 'html/form.html';
            } elseif ($_GET['p'] == 'map') {
                include 'html/map.html';
            }
        }

        // Afficher une erreur si le paramètre 'error' est défini dans l'URL
        if (isset($_GET['error']) && $_GET['error'] == '1') {
            echo "<script>document.getElementById('error').style.display = 'block';</script>";
        }

        // Vérifier si l'utilisateur est autorisé et s'il a une URL définie en session
        if (isset($_SESSION['url'], $_SESSION['autorise']) && $_SESSION['url'] != null && $_SESSION['autorise'] == 'oui') {
            echo "<script>
                document.getElementById('btn3').href = 'drone/" . $_SESSION['url'] . "';
                document.getElementById('userValue').textContent = '" . $_SESSION['login'] . "';
                document.getElementById('login-btn').style.display = 'none';
                document.getElementById('disconnection-btn').style.display = 'block';
            </script>";

            // Vérifier si l'ID de l'utilisateur est défini dans l'URL et en session
            if (isset($_GET['utilisateur'], $_SESSION['id']) && $_GET['utilisateur'] == $_SESSION['login']  && $_GET['id'] == $_SESSION['id']) {
                require 'php/parcours_drone.php';
            } 
        } else {
            echo "<script>document.getElementById('userValue').textContent = 'Utilisateur';</script>";
        }

        if(isset($_SESSION['autorise']) && $_SESSION['autorise'] == 'oui' && isset($_GET['p']) && $_GET['p'] == 'login') {
            // Détruire la session ouverte
            session_destroy();
        }    

    } else {
        echo "Page introuvable";
    }
    ?>

    <!-- JavaScript files -->
    <script src="js/script.js"></script> 
    <script src="js/bootstrap.bundle.min.js"></script>
</body>

</html>
