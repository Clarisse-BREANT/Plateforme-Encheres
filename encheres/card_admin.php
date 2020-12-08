

<!-- CODE HTML POUR LA GENERATION DU TABLEAU DES ENCHERES POUR L'ADMINISTRATEUR -->

<tr>
            <td><?php echo html($this->m_id); ?></td>
            <td><?php echo html($this->m_name); ?></td>
            <td><?php echo html($this->m_price); ?></td>
            <td><?php echo html($this->m_time); ?></td>
            <td><?php echo html($this->m_image); ?></td>
            <td><?php echo html($this->m_desc); ?></td>
            <td><?php echo html($this->m_stepprice); ?></td>
            <td><?php echo html($this->m_steptime); ?></td>

            <td style='width:15%;'>
                <div class='text-center d-flex justify-content-around'>
                    <form method="POST" action="../secur_admin/admin.php">
                        <button formmethod='POST' class='btn btn-warning'>
                            <i class='fas fa-eye'>
                                <input type="hidden" name='show_hide' value="<?php echo html($this->m_id); ?>" formmethod="POST">
                            </i>
                        </button>
                    </form> 

                    <form method="POST" action="../secur_admin/admin.php">
                        <button formmethod='POST' class='btn btn-primary'>
                            <i class='fas fa-edit' style='color:black;'>
                                <input type="hidden" name='edit' value="<?php echo html($this->m_id); ?>" formmethod="POST">
                            </i>
                        </button>
                    </form>

                    <form method="POST" action="../secur_admin/admin.php">
                        <button formmethod='POST' class='btn btn-danger'>
                            <i class='fas fa-trash-alt' style='color:black;'>
                                <input type="hidden" name='delete' value="<?php echo html($this->m_id); ?>" formmethod="POST">
                            </i>
                        </button>
                    </form>
                </div>
            </td>
</tr>