

<!-- PAGE PRINCIPALE DE LA PLATEFORME -->

<!doctype html>
<html lang="fr">
  <head>
    <title>Toto Corp Enchère</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
  </head>
<?php include '../scripts/file.php';
   include '../scripts/class_enchere.php';
   include '../scripts/htmlentities.php';
?>
  <body>
        <div class='container-fluid'>
            <!-- HEADER -->
            <header class='d-flex flex-column justify-content-around'>
              <?php  
                  $file = "log";
                  $logJson = load($file);
                  session_start();
                  //Si l'administrateur est connecté, alors le bouton Administrateur le conduit à la page d'admin, sinon il conduit l'utilisateur à la page de connection admin.
                  if (isset($_SESSION['connexion']) && $_SESSION['connexion'] == "administrateur" ){
              ?>
                    <form action="../secur_admin/admin.php" method='POST'>
                      <button formmethod='post' type='submit' value='admin' class='btn btn-warning align-self-end' >Administrateur</button>
                    </form>
                      
              <?php
                  }
                  else {
              ?>
                    <form action="../secur_admin/log_in.php" method='POST'>
                      <button formmethod='post' type='submit' value='login' class='btn btn-warning align-self-end' >Administrateur</button>
                    </form>
              <?php
                  }
              ?>
                <h1 class='align-self-center'>Nom de la Plateforme</h1>
            </header>

            <!-- SECTION DES ENCHERES -->
            <section>
              <div class='container'>
                <div class='card-deck row row-col-3 d-flex justify-content-around text-center'>
                      <?php 
                      //On charge les données du Json dans un tableau
                      $file = "encheres";
                      $cartonJson = load($file);
                      $carton = [];
                      $card="card";
                      $off = 0;

                      //Pour chaque élément du tableau de données, on regarde si le temps restant de l'enchère est toujours valide. Si oui on l'ajoute au tableau $carton, sinon, l'enchère ne sera pas sauvegardée.
                      for ($i = 0; $i < count($cartonJson); $i++) {
                          if ($cartonJson[$i]["m_time"] > time() 
                            && $cartonJson[$i]['m_status'] != 'deleted' ){
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
                      
                      if ($off > 0) {
                        save($carton, $file);
                      }

                      //Si un utilisateur clic sur le bouton d'enchère:
                        //On cherche quel est l'enchère concerné par le clic
                        //Ensuite on lui applique la fonction enchere qui augmente le prix et la durée de l'enchère
                        //On enregistre ensuite les nouvelles données
                      if(isset($_POST['encherir'])) {
                        $seeker = 0;
                        $target = -1;
                        while ($target == -1 && $seeker < count($carton)) {
                          if ($carton[$seeker]->getId() == $_POST['encherir']) {
                            $target = $seeker;
                          }
                          $seeker++;
                        }
                        if ($target != -1){
                          $carton[$target]->enchere();
                          save($carton, $file);
                          //Pour défaire la variable $_POST et éviter que les incrémentations se fasse à chaque rafraichissement
                          header('Location:../encheres/index.php');
                        }
                      }

                      //Pour chaque éléments sauvegardé dans $carton, on affiche les cartes d'enchères
                      for ($i = 0; $i < count($carton); $i++) {
                        if ($carton[$i]->getStatus() == 'active'){
                            $carton[$i]->display($card);}
                      }

                      ?>
                </div>
              </div>
            </section>

            <!-- FOOTER -->
            <footer>
              <a href="#" class='text-muted'>Mention Légale</a>
              <a href="#" class='text-muted'>Condition de ventes</a>
              <a href="#" class='text-muted'>Contact</a>
            </footer>
        </div>

  </body>
</html>