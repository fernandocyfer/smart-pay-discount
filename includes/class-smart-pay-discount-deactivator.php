<?php
/**
 * Fired during plugin deactivation
 *
 * @link       https://github.com/fernandofilho
 * @since      1.0.0
 *
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/includes
 * @author     Fernando Filho
 */
class Smart_Pay_Discount_Deactivator {

    /**
     * Método executado durante a desativação do plugin.
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        // Não removemos as opções para manter as configurações do usuário
    }
} 