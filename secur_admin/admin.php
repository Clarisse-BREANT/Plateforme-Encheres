<?php
session_start();
include '../scripts/log_in.php';
include '../scripts/class_enchere.php';
include '../scripts/htmlentities.php';

//Si la variable $_SESSION['connexion'] est vide et qu'il existe un message d'erreur (ergo, l'utilisateur a tenté de se connecté en vain)
  //Alors on le redirige vers la page de login à laquelle on ajoute en url la variable $err (correspondant à l'erreur de l'utilisateur sur sa tentative de connexion) accessible par la méthode GET
if(empty($_SESSION['connexion']) || isset($err)) {
  header('Location:../secur_admin/log_in.php?err=' . $err);
}
elseif(empty($_SESSION['connexion']) && !isset($err)){
  header('Location:../secur_admin/log_in.php');
}

//On charge les données du Json dans un tableau
$file="encheres";
$cartonJson = load($file);
$carton = [];
$card="card_admin";
$off = 0;
//Pour chaque élément du tableau de données, on regarde si le temps restant de l'enchère est toujours valide. Si oui on l'ajoute au tableau $carton, sinon, l'enchère ne sera pas sauvegardée.
for ($i = 0; $i < count($cartonJson); $i++) {
    if ($cartonJson[$i]["m_time"] > time() && $cartonJson[$i]["m_status"] != "deleted" ){
      $carton[$i-$off] = new Enchere(
                                $cartonJson[$i]['m_id'],
                                $cartonJson[$i]['m_name'],
                                $cartonJson[$i]['m_price'],
                                $cartonJson[$i]['m_time'],
                                $cartonJson[$i]['m_image'],
                                $cartonJson[$i]['m_desc'],
                                $cartonJson[$i]['m_steptime'],
                                $cartonJson[$i]['m_stepprice'],
                                $cartonJson[$i]['m_status']
      );
    }
    else{
      $off++;
    }
}

// MANAGE

$manage = '';

//Si la carte n'est pas éditée, le formulaire de base contiendra les données ci dessous
$f_id = sizeof($carton);
$f_name = '';
$f_price = 0;
$f_time = 0;
$f_image = '';
$f_desc = 'Entrez une description';
$f_steptime = 0;
$f_stepprice = 1;

//Si il y a ey une requête POST, on définie la variable $manage
if( isset($_POST['show_hide']) || isset($_POST['edit']) || isset($_POST['delete']) ) {
  //On change la variable $manage selon quel bouton a été appuyé
  if (isset($_POST['show_hide'])) {
    $manage = 'show_hide';
  }
  elseif (isset($_POST['edit'])){
    $manage = 'edit';
  }
  elseif (isset($_POST['delete'])){
    $manage='delete';
  }
  else {echo html('ERROR, WRONG POST FOUND');}

  //Puis on cherche quel est l'enchère associé à l'action
    $seeker = 0;
    $target = -1;
      //On cherche l'id de l'enchère concerné par le clic
      while ($target == -1 && $seeker < count($carton)) {
        if ( ($carton[$seeker]->getId() == $_POST[$manage]) ) {
          $target = $seeker;
        }
        $seeker++;
      }

      //Lorsqu'on l'a trouvé on fait une action qui varie selon le bouton cliqué
      if ($target != -1) {
        //On change le status de la carte
          if ($manage == 'show_hide') {
              $carton[$target]->changeStatus();
              save($carton, $file);
          }

          //On supprime la carte
          elseif ($manage == 'delete') {
            $carton[$target]->deleteCard();
            save($carton, $file);
            header('Location:../secur_admin/admin.php');
          }

          //On edit la carte
          elseif ($manage == 'edit') {
            //Les variables qui seront dans le formulaire, prennent les valeurs de l'enchère selectionnee 
            $f_id = $carton[$target]->getId();
            $f_name = $carton[$target]->getName();
            $f_price = $carton[$target]->getPrice();
            $f_time = $carton[$target]->getTime();
            $f_image = $carton[$target]->getImage();
            $f_desc = $carton[$target]->getDesc();
            $f_steptime = $carton[$target]->getStepTime();
            $f_stepprice = $carton[$target]->getStepPrice();
            $carton[$target]->deleteCard();
            save($carton, $file);
          }
      }  
}
?>

<!-- PAGE RESERVEE A L'ADMINISTRATEUR LUI PERMETTANT DE GERER LES ENCHERES ET D'EN CREER DE NOUVELLES -->


<!doctype html>
<html lang="fr">
  <head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <link rel="stylesheet" href="../styles/admin.css">
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
  <div class='container-fluid'>
            <!--HEADER-->
            <header class='d-flex flex-column'>
                <form class='align-self-end' action="../secur_admin/log_in.php">
                  <button class='btn btn-danger' name="logout" formmethod="POST" type='submit'> DECONNEXION </button>
                </form>
                <!--<form class='align-self-end' action="../secur_admin/log_in.php">
                  <button class='btn btn-dark' name="login_edit" formmethod="POST" type='submit'> Modifier les identifiants </button>
                </form>-->
                <h1 class='align-self-center text-center'>Nom de la Plateforme</h1>
            </header>
            <!--CONTENU-->
            <div class='container d-flex flex-column align-items-center'>
                <!--FORMULAIRE-->
                <?php 
                //Selon si la carte doit être éditée ou non on change le titre
                  if (isset($_POST['edit'])){
                ?>
                <form action="../scripts/form.php" method='post' class='w-50 form my-5'>
                  <h3 class='text'>Modifier l'enchère</h3>

                <?php
                  }
                  else {
                ?>
                <form action="../scripts/form.php" method='post' class='w-50 form my-5'>
                  <h3 class='text'>Créer une nouvelle enchère</h3>
                <?php
                  }
                ?>
                  
                  <input type="hidden" value='<?php echo html($f_id); ?>' name='id' id='product_id' formmethod="post" >

                  <label for="product_name">Nom du produit</label>
                  <input required class='form-control' type="text" name='name' id='product_name' formmethod="post" 
                    value='<?php echo html($f_name); ?>'>

                  <label for="price">Prix du produit</label>
                  <input required min='1'class='form-control' type="number" name='price' id='price' formmethod="post"
                    value='<?php echo html($f_price); ?>'>

                  <label for="time">Date d'échéance de l'enchère</label>
                  <input required class='form-control' type="date" name='time' id='time' formmethod="post"
                    value='<?php echo html($f_time); ?>'>

                  <label for="image">Image du produit</label>
                  <input class='form-control' type="file" name='image' id='image' formmethod="post"
                    value='<?php echo html($f_image); ?>'>

                  <label for="desc">Description du produit</label>
                  <textarea class='form-control' cols="30" rows="5" name='desc' id='desc' formmethod="post"
                  value='<?php echo html($f_desc); ?>'><?php echo html($f_desc); ?></textarea>

                  <label for="steptime">Augmentation du temps par clic</label>
                  <input required min='0' class='form-control' type="number" name='steptime' id='steptime' formmethod="post"
                    value='<?php echo html($f_steptime); ?>'>

                  <label for="stepprice">Augmentation du prix par clic</label>
                  <input required min='1' class='form-control' type="number" name='stepprice' id='stepprice' formmethod="post"
                    value='<?php echo html($f_stepprice); ?>'>

                <?php 
                //Selon si la carte doit être éditée ou non on change le bouton et son action
                  if (isset($_POST['edit'])){
                ?>
                <input class='form-control bg-success my-3' style='color:white;' type="submit" value="Modifier l'enchère" formmethod="post">

                <?php
                  }
                  else {
                ?>
                  <input class='form-control bg-success my-3' style='color:white;' type="submit" value='Créer une nouvelle enchère' formmethod="post">
                <?php
                  }
                ?>
                </form>
                
                <!--TABLEAU DES ENCHERES-->

                <h3>Enchère en cours</h3>
                <div class='gestion-article d-flex w-100'>
                  <table class='text-center w-100'>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Nom</th>
                            <th>Prix</th>
                            <th>Temps</th>
                            <th>Image</th>
                            <th>Description</th>
                            <th>+Prix</th>
                            <th>+Temps</th>
                            <th>Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php   
                      //Une fois que toutes les enchères encore valide sont chargées ont les affiche
                      if ($off > 0) {
                        save($carton, $file);
                      }

                      for ($i = 0; $i < count($carton); $i++) {
                        $carton[$i]->display($card);
                      }

                      ?>
                      </tbody>
                    </table>
                </div>
            </div>

            <!--FOOTER-->
            <footer>
              <a href="#" class='text-muted'>Mention Légale</a>
              <a href="#" class='text-muted'>Condition de ventes</a>
              <a href="#" class='text-muted'>Contact</a>
            </footer>
        </div>
  </body>
</html>