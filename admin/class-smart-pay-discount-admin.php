<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/fernandofilho
 * @since      1.0.0
 *
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Smart_Pay_Discount
 * @subpackage Smart_Pay_Discount/admin
 * @author     Fernando Filho
 */
class Smart_Pay_Discount_Admin {

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
     * @param    string    $plugin_name       The name of this plugin.
     * @param    string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {
        // Carrega o Select2 CSS
        wp_enqueue_style('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', array(), '4.1.0');
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/smart-pay-discount-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        // Carrega o Select2 JS
        wp_enqueue_script('select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array('jquery'), '4.1.0', true);
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/smart-pay-discount-admin.js', array('jquery', 'select2'), $this->version, false);
    }

    /**
     * Add menu items to the admin menu.
     *
     * @since    1.0.0
     */
    public function add_plugin_admin_menu() {
        add_submenu_page(
            'woocommerce',
            __('Smart Pay Discount', 'smart-pay-discount'),
            __('Smart Pay Discount', 'smart-pay-discount'),
            'manage_options',
            $this->plugin_name,
            array($this, 'display_plugin_admin_page')
        );
    }

    /**
     * Register settings for the plugin.
     *
     * @since    1.0.0
     */
    public function register_settings() {
        register_setting(
            'spd_settings',
            'spd_settings',
            array($this, 'validate_settings')
        );

        add_settings_section(
            'spd_general_section',
            __('Configurações Gerais', 'smart-pay-discount'),
            array($this, 'general_section_callback'),
            'spd_settings'
        );

        add_settings_field(
            'enabled',
            __('Ativar Plugin', 'smart-pay-discount'),
            array($this, 'enabled_callback'),
            'spd_settings',
            'spd_general_section'
        );

        add_settings_field(
            'discount_percentage',
            __('Percentual de Desconto (%)', 'smart-pay-discount'),
            array($this, 'discount_percentage_callback'),
            'spd_settings',
            'spd_general_section'
        );

        add_settings_field(
            'payment_methods',
            __('Métodos de Pagamento', 'smart-pay-discount'),
            array($this, 'payment_methods_callback'),
            'spd_settings',
            'spd_general_section'
        );

        add_settings_field(
            'excluded_categories',
            __('Categorias Excluídas', 'smart-pay-discount'),
            array($this, 'excluded_categories_callback'),
            'spd_settings',
            'spd_general_section'
        );

        add_settings_field(
            'ignore_with_coupon',
            __('Ignorar com Cupom', 'smart-pay-discount'),
            array($this, 'ignore_with_coupon_callback'),
            'spd_settings',
            'spd_general_section'
        );

        add_settings_field(
            'discount_text',
            __('Texto do Desconto', 'smart-pay-discount'),
            array($this, 'discount_text_callback'),
            'spd_settings',
            'spd_general_section'
        );
    }

    /**
     * Render the settings page for this plugin.
     *
     * @since    1.0.0
     */
    public function display_plugin_admin_page() {
        include_once 'partials/smart-pay-discount-admin-display.php';
    }

    /**
     * General section callback.
     *
     * @since    1.0.0
     */
    public function general_section_callback() {
        echo '<p>' . __('Configure as opções do Smart Pay Discount.', 'smart-pay-discount') . '</p>';
    }

    /**
     * Enabled field callback.
     *
     * @since    1.0.0
     */
    public function enabled_callback() {
        $options = get_option('spd_settings');
        $enabled = isset($options['enabled']) ? $options['enabled'] : 'no';
        ?>
        <label>
            <input type="checkbox" name="spd_settings[enabled]" value="yes" <?php checked($enabled, 'yes'); ?> />
            <?php _e('Ativar o plugin', 'smart-pay-discount'); ?>
        </label>
        <?php
    }

    /**
     * Discount percentage field callback.
     *
     * @since    1.0.0
     */
    public function discount_percentage_callback() {
        $options = get_option('spd_settings');
        $discount = isset($options['discount_percentage']) ? $options['discount_percentage'] : '5';
        ?>
        <input type="number" name="spd_settings[discount_percentage]" value="<?php echo esc_attr($discount); ?>" min="0" max="100" step="0.01" />
        <?php
    }

    /**
     * Payment methods field callback.
     *
     * @since    1.0.0
     */
    public function payment_methods_callback() {
        $options = get_option('spd_settings');
        $selected_methods = isset($options['payment_methods']) ? $options['payment_methods'] : array();
        $payment_gateways = WC()->payment_gateways->payment_gateways();

        foreach ($payment_gateways as $gateway) {
            if ($gateway->enabled === 'yes') {
                ?>
                <label style="display: block; margin-bottom: 5px;">
                    <input type="checkbox" name="spd_settings[payment_methods][]" value="<?php echo esc_attr($gateway->id); ?>" <?php checked(in_array($gateway->id, $selected_methods)); ?> />
                    <?php echo esc_html($gateway->get_title()); ?>
                </label>
                <?php
            }
        }
    }

    /**
     * Excluded categories field callback.
     *
     * @since    1.0.0
     */
    public function excluded_categories_callback() {
        $options = get_option('spd_settings');
        $excluded_categories = isset($options['excluded_categories']) ? $options['excluded_categories'] : array();
        $product_categories = get_terms('product_cat', array('hide_empty' => false));

        ?>
        <select name="spd_settings[excluded_categories][]" class="spd-categories-select" multiple="multiple">
            <?php foreach ($product_categories as $category) : ?>
                <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected(in_array($category->term_id, $excluded_categories)); ?>>
                    <?php echo esc_html($category->name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php
    }

    /**
     * Ignore with coupon field callback.
     *
     * @since    1.0.0
     */
    public function ignore_with_coupon_callback() {
        $options = get_option('spd_settings');
        $ignore_with_coupon = isset($options['ignore_with_coupon']) ? $options['ignore_with_coupon'] : 'yes';
        ?>
        <label>
            <input type="checkbox" name="spd_settings[ignore_with_coupon]" value="yes" <?php checked($ignore_with_coupon, 'yes'); ?> />
            <?php _e('Ignorar desconto quando houver cupom aplicado', 'smart-pay-discount'); ?>
        </label>
        <?php
    }

    /**
     * Discount text field callback.
     *
     * @since    1.0.0
     */
    public function discount_text_callback() {
        $options = get_option('spd_settings');
        $discount_text = isset($options['discount_text']) ? $options['discount_text'] : 'Desconto de %s%%';
        ?>
        <input type="text" name="spd_settings[discount_text]" value="<?php echo esc_attr($discount_text); ?>" class="regular-text" />
        <p class="description"><?php _e('Personalize o texto que aparece no carrinho. Use %s para a porcentagem do desconto.', 'smart-pay-discount'); ?></p>
        <?php
    }

    /**
     * Validate settings.
     *
     * @since    1.0.0
     * @param    array    $input    The settings input.
     * @return   array              The validated settings.
     */
    public function validate_settings($input) {
        $output = array();

        $output['enabled'] = isset($input['enabled']) ? 'yes' : 'no';
        $output['discount_percentage'] = isset($input['discount_percentage']) ? floatval($input['discount_percentage']) : 5;
        $output['payment_methods'] = isset($input['payment_methods']) ? (array) $input['payment_methods'] : array();
        $output['excluded_categories'] = isset($input['excluded_categories']) ? (array) $input['excluded_categories'] : array();
        $output['ignore_with_coupon'] = isset($input['ignore_with_coupon']) ? 'yes' : 'no';
        $output['discount_text'] = isset($input['discount_text']) ? $input['discount_text'] : 'Desconto de %s%%';

        return $output;
    }

    public function save_settings() {
        if (isset($_POST['spd_settings'])) {
            $settings = $_POST['spd_settings'];
            
            // Sanitiza e salva as configurações
            $sanitized_settings = array(
                'enabled' => isset($settings['enabled']) ? 'yes' : 'no',
                'discount_percentage' => isset($settings['discount_percentage']) ? floatval($settings['discount_percentage']) : 5,
                'payment_methods' => isset($settings['payment_methods']) ? array_map('sanitize_text_field', $settings['payment_methods']) : array(),
                'excluded_categories' => isset($settings['excluded_categories']) ? array_map('intval', $settings['excluded_categories']) : array(),
                'ignore_with_coupon' => isset($settings['ignore_with_coupon']) ? 'yes' : 'no',
                'discount_text' => isset($settings['discount_text']) ? sanitize_text_field($settings['discount_text']) : 'Desconto de %s%%'
            );
            
            update_option('spd_settings', $sanitized_settings);
        }
    }
} 