<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/fernandofilho
 * @since      1.0.0
 *
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/public
 * @author     Fernando Filho
 */
class Smart_Pay_Discount_Public {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param    string    $plugin_name       The name of the plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/smart-pay-discount-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/smart-pay-discount-public.js', array('jquery'), $this->version, false);
        wp_localize_script($this->plugin_name, 'spd_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('spd_nonce')
        ));
    }

    /**
     * Adiciona o desconto ao carrinho.
     *
     * @since    1.0.0
     * @param    WC_Cart    $cart    O objeto do carrinho.
     */
    public function add_discount_to_cart($cart) {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        $options = get_option('spd_settings');
        if (!isset($options['enabled']) || $options['enabled'] !== 'yes') {
            return;
        }

        // Verifica se há cupom aplicado
        if (isset($options['ignore_with_coupon']) && $options['ignore_with_coupon'] === 'yes' && $cart->get_applied_coupons()) {
            return;
        }

        // Obtém o método de pagamento selecionado
        $chosen_payment_method = WC()->session->get('chosen_payment_method');
        if (!$chosen_payment_method || !in_array($chosen_payment_method, $options['payment_methods'])) {
            return;
        }

        // Obtém o gateway de pagamento
        $payment_gateways = WC()->payment_gateways->payment_gateways();
        if (!isset($payment_gateways[$chosen_payment_method])) {
            return;
        }

        $gateway = $payment_gateways[$chosen_payment_method];
        $discount_percentage = isset($options['discount_percentage']) ? floatval($options['discount_percentage']) : 5;

        // Obtém as categorias excluídas
        $excluded_categories = isset($options['excluded_categories']) ? $options['excluded_categories'] : array();
        
        // Calcula o total dos produtos que podem receber desconto
        $discountable_total = 0;
        foreach ($cart->get_cart() as $cart_item) {
            $product_categories = get_the_terms($cart_item['product_id'], 'product_cat');
            $is_excluded = false;
            
            if ($product_categories) {
                foreach ($product_categories as $category) {
                    if (in_array($category->term_id, $excluded_categories)) {
                        $is_excluded = true;
                        break;
                    }
                }
            }
            
            if (!$is_excluded) {
                $discountable_total += $cart_item['line_subtotal'];
            }
        }

        // Calcula e aplica o desconto apenas no total dos produtos que podem receber desconto
        if ($discountable_total > 0) {
            $discount_amount = ($discountable_total * $discount_percentage) / 100;
            
            // Obtém o texto personalizado do desconto
            $discount_text = isset($options['discount_text']) ? $options['discount_text'] : 'Desconto de %s%%';
            
            // Adiciona o desconto ao carrinho
            $cart->add_fee(
                sprintf($discount_text, $discount_percentage),
                -$discount_amount
            );
        }
    }

    /**
     * AJAX handler for updating payment discount.
     *
     * @since    1.0.0
     */
    public function ajax_update_payment_discount() {
        check_ajax_referer('spd_nonce', 'nonce');

        if (!isset($_POST['payment_method'])) {
            wp_send_json_error();
        }

        WC()->session->set('chosen_payment_method', sanitize_text_field($_POST['payment_method']));
        WC()->cart->calculate_totals();

        ob_start();
        woocommerce_mini_cart();
        $mini_cart = ob_get_clean();

        $data = array(
            'fragments' => apply_filters(
                'woocommerce_add_to_cart_fragments',
                array(
                    'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>'
                )
            ),
            'cart_hash' => WC()->cart->get_cart_hash()
        );

        wp_send_json($data);
    }
} 