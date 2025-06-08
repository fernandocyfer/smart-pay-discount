<?php
/**
 * Provide a admin area view for the plugin
 *
 * @link       https://github.com/fernandofilho
 * @since      1.0.0
 *
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/admin/partials
 */
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

    <form method="post" action="options.php">
        <?php
        settings_fields('spd_settings');
        do_settings_sections('spd_settings');
        submit_button();
        ?>
    </form>
</div> 