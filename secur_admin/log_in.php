<?php
include '../scripts/log_in.php';
include '../scripts/htmlentities.php';
?>

<!-- PAGE DE CONNEXION, RESERVEE A L'ADMINISTRATEUR AFIN D'AVOIR ACCES A LA PAGE DE GESTION DES ENCHERES-->

<!doctype html>
<html lang="fr">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <link rel="stylesheet" href="../styles/log_in.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  </head>

  <body>
      <?php 
    if (isset($_POST['login_edit'])) {
      ?>
      <form action="../secur_admin/admin.php" method="POST"> <!--redirection vers la page admin après envoi du formulaire-->
          <label for="identifiant_old">Ancien Identifiant:</label>
          <input type="text" placeholder='identifiant' id='identifiant_old' name='oldidentifiant' formmethod='POST' required>

          <label for="identifiant_new">Nouvel Identifiant:</label>
          <input type="text" placeholder='identifiant' id='identifiant_new' name='newidentifiant' formmethod='POST'>

          <label for="mot_de_passe_old">Mot de passe actuel:</label>
          <input type="password" placeholder='password' id='mot_de_passe_old' name='oldpassword' formmethod='POST' required>

          <label for="mot_de_passe_new">Nouveau mot de passe:</label>
          <input type="password" placeholder='password' id='mot_de_passe_new' name='newpassword' formmethod='POST'>
          
          <button type="submit" class="btn btn-primary">Modifier</button> <!-- Il faudra trouver un autre moyen que submit-->
      </form>
      <?php
    }
    else {
  ?>
    <form action="../secur_admin/admin.php" method="POST"> <!--redirection vers la page admin après envoi du formulaire-->
          <label for="identifiant">Identifiant:</label>
          <input type="text" placeholder='admin' id='identifiant' name='identifiant' formmethod='POST' required>
          <label for="mot-de-passe">Mot de passe:</label>
          <input type="password" placeholder='password' id='mot-de-passe' name='password' formmethod='POST' required>
          <button type="submit" class="btn btn-primary">Se connecter</button> <!-- Il faudra trouver un autre moyen que submit-->
      </form>
      <?php
    }
        //Si l'utilisateur se trouve sur la page de log_in de laquelle l'url contient la variable $err
          //Alors on récupère variable $err dans l'url par $_GET('err') et on affiche un message d'erreur associé à celle ci 
        session_start();
        
        if (isset($_GET["err"])){
          if ($_GET["err"] == "00"){
            echo html("Mot de passe ou identifiant erronés.");
          }
          elseif ($_GET['err']=='03') {
            echo html('Accès refusé, veuillez vous connecter pour accéder à cette page.');
          }
          elseif ($_GET['err'] = '01'){
            echo html("Echec de la modification des identifiants, anciens identifiants erronés.");
          }
          elseif ($_GET['err'] = '02') {
            echo html("Veuillez entrer vos anciens identifiants afin de les modifier.");
          }
        }
      ?>
  </body>
</html>