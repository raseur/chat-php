<?php

// Connexion à la base de données
$db = new PDO('sqlite:chat.db');

// Création de la table des messages s'il n'existe pas déjà
$db->exec("CREATE TABLE IF NOT EXISTS messages (id INTEGER PRIMARY KEY, username TEXT, message TEXT, created_at DATETIME DEFAULT CURRENT_TIMESTAMP)");

session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['username'])) {
  if (isset($_POST['login'])) {
    // Authentification de l'utilisateur
    $_SESSION['username'] = $_POST['username'];
  } else {
    // Affichage du formulaire de connexion
    ?>
    <form action="" method="post">
      <label for="username">Nom d'utilisateur :</label><br>
      <input type="text" name="username" id="username"><br><br>
      <input type="submit" name="login" value="Connexion">
    </form>
    <?php
    exit;
  }
}

// Envoi d'un nouveau message
if (isset($_POST['message'])) {
  $query = $db->prepare('INSERT INTO messages (username, message) VALUES (:username, :message)');
  $query->execute(array(
    'username' => $_SESSION['username'],
    'message' => $_POST['message']
  ));
}

// Récupération des 10 derniers messages
$query = $db->query('SELECT * FROM messages ORDER BY id DESC LIMIT 10');
$messages = $query->fetchAll();

// Affichage des messages
foreach ($messages as $message) {
  echo $message['username'] . ' : ' . $message['message'] . '<br>';
}

// Formulaire pour envoyer un nouveau message
?>
<form action="" method="post">
  <input type="text" name="message"><input type="submit" value="Envoyer">
</form>
<a href="?logout">Déconnexion</a>
