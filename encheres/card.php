

<!-- CODE HTML POUR LA GENERATION DES CARTES POUR LES UTILISATEURS -->

<div class='card-container col-lg-4 col text-center my-4'>
    <div class='card'>
        <div class='card-header'>
            <h5 class='card-title font-weight-bold'>Nom du produit : <?php echo html($this->m_name); ?></h5>
        </div>
        <div class='card-body'>
            <h5 class='card-title font-weight-bold' style='color:red;'><?php echo html($this->m_price); ?>€</h5>
            <img class='card-img' src="<?php echo html($this->m_image); ?>" alt="Iphone">
            <p class='card-text font-weight-bold' style='color:red;'><?php echo date("d\J H\h i\m s\s", html($this->m_time - time() - 3600*3)); ?> </p>
            <form method="POST">
                <button class='btn btn-warning w-75' formmethod='POST'>ACHETER
                    <input type="hidden" name="encherir" value="<?php echo html($this->m_id); ?>" formmethod="POST">
                </button>
            </form>
        </div>
        <div class='card-footer'>
            <div class='card-text'>
                <p> <?php echo html($this->m_desc); ?> </p>
                <p>Temps : +<?php echo html($this->m_steptime); ?> secondes/clic</p>
                <p>Prix : +<?php echo html($this->m_stepprice); ?> centimes/clic</p>
            </div>
        </div>
    </div>
</div>