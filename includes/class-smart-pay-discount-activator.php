<?php
/**
 * Fired during plugin activation
 *
 * @link       https://github.com/fernandofilho
 * @since      1.0.0
 *
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/includes
 * @author     Fernando Filho
 */
class Smart_Pay_Discount_Activator {

    /**
     * Método executado durante a ativação do plugin.
     *
     * @since    1.0.0
     */
    public static function activate() {
        // Adiciona as opções padrão do plugin
        $default_options = array(
            'enabled' => 'yes',
            'discount_percentage' => '5',
            'payment_methods' => array(),
            'excluded_categories' => array(),
            'ignore_with_coupon' => 'yes'
        );

        add_option('spd_settings', $default_options);
    }
} 