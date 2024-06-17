<?php


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {

    $tab = array("map", "accueil");

    $found = false;

    for ($j = 0; $j < 2; $j++) {
        if ($_POST['search'] == $tab[$j]) {
            $url = '../?p=' . urlencode($tab[$j]);
            header("Location: $url");
            exit; 
        }
    }

    if (!$found) {
        header("Location: ../");
        exit;
    }

}
