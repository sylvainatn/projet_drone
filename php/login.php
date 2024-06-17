<?php

include 'db_config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['login'], $_POST['mdp'])) {

        // Supprimer les espaces au début et à la fin de la chaîne de caractère
        $login = trim($_POST['login']);
        $mdp = trim($_POST['mdp']);

        if (!empty($login) && !empty($mdp)) {
            
            // Préparer et exécuter la requête SQL
            $sql = "SELECT id, mdp FROM drone.utilisateur WHERE login = :login";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':login', $login, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result && strcmp(md5($mdp), $result['mdp']) == 0) {
                // Générer un ID utilisateur unique pour la session
                $user_id = uniqid();

                // Stocker les informations de session
                $_SESSION['autorise'] = 'oui';
                $_SESSION['id'] = $user_id;
                $_SESSION['login'] = htmlspecialchars($login);
                $url = '../?utilisateur=' . urlencode($login) . '&id=' . urlencode($user_id);
                $_SESSION['url'] = $url;

                // Redirection vers l'URL définie
                header("Location: $url");
                exit;
            } else {
                // Redirection en cas d'erreur de connexion
                header('Location: ../?p=login&error=1');
                exit;
            }
        } else {
            // Redirection en cas de champs vides
            header('Location: ../?p=login&error=1');
            exit;
        }
    }
}

?>
