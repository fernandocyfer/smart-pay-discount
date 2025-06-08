<?php
/**
 * The plugin bootstrap file
 *
 * @link              https://github.com/fernandocyfer
 * @since             1.0.0
 * @package           Smart_Pay_Discount
 *
 * @wordpress-plugin
 * Plugin Name:       Smart Pay - Discount Per Payment Method
 * Plugin URI:        https://github.com/fernandocyfer/smart-pay-discount
 * Description:       Aplica descontos automáticos baseados no método de pagamento selecionado no WooCommerce.
 * Version:           1.0.0
 * Author:            Cyfer Development
 * Author URI:        https://www.cyfer.com.br/
 * Contributors:      cyferweb
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       smart-pay-discount
 * Domain Path:       /languages
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * WC requires at least: 5.0
 * WC tested up to:   8.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 */
define('SMART_PAY_DISCOUNT_VERSION', '1.0.0');
define('SMART_PAY_DISCOUNT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SMART_PAY_DISCOUNT_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The code that runs during plugin activation.
 */
function activate_smart_pay_discount() {
    require_once SMART_PAY_DISCOUNT_PLUGIN_DIR . 'includes/class-smart-pay-discount-activator.php';
    Smart_Pay_Discount_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_smart_pay_discount() {
    require_once SMART_PAY_DISCOUNT_PLUGIN_DIR . 'includes/class-smart-pay-discount-deactivator.php';
    Smart_Pay_Discount_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_smart_pay_discount');
register_deactivation_hook(__FILE__, 'deactivate_smart_pay_discount');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require SMART_PAY_DISCOUNT_PLUGIN_DIR . 'includes/class-smart-pay-discount.php';

/**
 * Begins execution of the plugin.
 */
function run_smart_pay_discount() {
    $plugin = new Smart_Pay_Discount();
    $plugin->run();
}

// Verifica se o WooCommerce está ativo
function spd_check_woocommerce() {
    if (!class_exists('WooCommerce')) {
        add_action('admin_notices', 'spd_woocommerce_notice');
        return;
    }
    run_smart_pay_discount();
}
add_action('plugins_loaded', 'spd_check_woocommerce');

/**
 * Admin notice for WooCommerce dependency
 */
function spd_woocommerce_notice() {
    ?>
    <div class="error">
        <p><?php _e('Smart Pay Discount requer o WooCommerce ativo para funcionar.', 'smart-pay-discount'); ?></p>
    </div>
    <?php
} 