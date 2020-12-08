<?php
include '../scripts/file.php';
/**
  GESTION DE LA CONNEXION ADMIN
*/

$file = "log";
$logJson = load($file); //Chargement des données de connexion

//Dans tous les cas l'utilisateur sera ammené à la page admin.php à l'envoi du formulaire


//DECONNEXION ADMIN
//Si le bouton de déconnexion est cliqué, il envoit un post qui supprime la variable $_SESSION['connexion'] et donc qui déconnecte l'utilisateur
if (isset($_POST['logout'])){
    if (isset($_SESSION['connexion'])) {
        unset($_SESSION['connexion']); 
        destroy($_SESSION['connexion']);
    }
}


//GESTION DES IDENTIFIANTS
/*if ( isset($_POST['login_edit']) )  {
    if ( isset($_POST['oldidentifiant']) && isset($_POST['oldpassword']) ) {
        if ( $_POST['oldpassword']==$logJson[0]['password'] && $_POST['oldidentifiant']==$logJson[0]['identifiant'] ){
            if ( isset($_POST['newidentifiant']) ){
                $logJson[0]["identifiant"] = $_POST['newidentifiant'];
            }
            if ( isset($_POST['newpassword']) ){
                $logJson[0]["password"] = $_POST['newpassword'];
            }   
        }
        else{
            $err='01';
        }
    }
    else { $err='02';}
    save($logJson,$file);
}
*/

//CONNEXION ADMIN
//Test des identifiants entrés par l'utilisateur

else {
    if (isset($_POST["identifiant"]) && isset($_POST["password"])){
        $password=$logJson[0]['password'];
        $identifiant=$logJson[0]['identifiant'];
        //Si le mot de passe ou l'identifiant est incorrect, on créer une variable d'erreur
        if (password_verify($_POST['identifiant'],$identifiant) && password_verify($_POST['password'], $password)){
            $_SESSION['connexion'] = "administrateur";
        }
        else{
            //La variable $_SESSION['connexion'] est initialisé, l'utilisateur est connecté
            $err = "00";
        }
    }   
    elseif (empty($_SESSION['connexion'])) {
        $err='03';
    }
}
?>