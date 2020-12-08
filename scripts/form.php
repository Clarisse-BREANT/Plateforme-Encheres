<?php

//SAUVEGARDE DES DONNEES DU FORMULAIRE DANS UN FICHIER JSON

include '../scripts/file.php';
include '../scripts/class_enchere.php';

//var_dump($_POST); Permet de visualiser les données envoyées
    
        //Si le formulaire a bien été envoyé et que les informations essentielles ne sont pas vide
            //Alors on charge les enchères, on recréer chaque objets à partir des données chargé du json si le temps est toujours valide
            //On insère la nouvelle enchère définit par l'envoie des données du formulaire
            //On sauvegarde le tout dans le fichier Json
            //On redirige vers la page publique des enchères afin de visualiser que l'enchère a bien été mise en ligne 
        if (isset($_POST['name'])      && $_POST['name']      != "" 
        && isset($_POST['price'])      && $_POST['price']     != "" 
        && isset($_POST['image'])    //&& $_POST['image']     != "" 
        && isset($_POST['time'])       && $_POST['time']      != "" 
        && isset($_POST['desc'])     //&& $_POST['desc']      != ""
        && isset($_POST['steptime'])   && $_POST['steptime']  != "" 
        && isset($_POST['stepprice'])  && $_POST['stepprice'] != ""){

                //Chargement enchères
                $file = "encheres";
                $cartonJson = load($file);
                $carton=[];
                $off=0;

                //Création des objets toujours existant php
                for ($i=0; $i < count($cartonJson); $i++){
                    if ($cartonJson[$i]['m_time'] > time() ) {
                        $carton[$i-$off] = new Enchere(
                                                $cartonJson[$i]["m_id"],
                                                $cartonJson[$i]["m_name"],
                                                $cartonJson[$i]["m_price"],
                                                $cartonJson[$i]["m_time"],
                                                $cartonJson[$i]["m_image"],
                                                $cartonJson[$i]["m_desc"],
                                                $cartonJson[$i]["m_steptime"],
                                                $cartonJson[$i]["m_stepprice"],
                                                $cartonJson[$i]["m_status"]
                                            );
                    } else {$off++;}
                }

                // CREATION ET INSERTION DE LA NOUVELLE ENCHERE
                $id=intval($_POST['id']);
                if (sizeof($_POST['name'])<255){
                    $name=strval($_POST["name"]);
                }
                if ($_POST['price']>1){
                    $price=floatval($_POST["price"]);
                }
                //$_POST['time'];
                if (sizeof($_POST['image']<255) && sizeof($_POST['desc'])<255){               
                    $image=strval($_POST["image"]);
                    $desc=strval($_POST["desc"]);
                }
                if ($_POST['steptime']>0){
                    $steptime=intval($_POST["steptime"]);
                }
                if ($_POST['stepprice'] > 1){
                    $stepprice=intval($_POST["stepprice"]);
                }
                $date_time= new DateTime($_POST['time']);
                $carton[$i - $off] = new Enchere(
                                                $id,
                                                $name,
                                                $price,
                                                $date_time->getTimestamp(),
                                                $image,
                                                $desc,
                                                $steptime,
                                                $stepprice
                );

                // ENREGISTREMENT DANS LE FICHIER JSON
                save($carton, $file);
                header('Location:../encheres/index.php');
                
            }
        


?>