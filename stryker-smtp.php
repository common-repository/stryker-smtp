<?php
/**
 * Plugin Name: Stryker SMTP
 * Description: Sends all emails through an SMTP server. Configure your SMTP credentials in wp-admin and send a test email.
 * Version: 1.0
 * Author: Darren Below
 * Author URI: https://darrenbelow.com.au/stryker-smtp
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: stryker-smtp
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('stryker_smtp_send')) {
    function stryker_smtp_send($phpmailer) {
        $options = get_option('stryker_smtp_settings');
        
        if (!empty($options['smtp_host']) && !empty($options['smtp_email']) && !empty($options['smtp_password'])) {
            $phpmailer->isSMTP();
            $phpmailer->Host       = sanitize_text_field($options['smtp_host']);
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Port       = intval($options['smtp_port']);
            $phpmailer->SMTPSecure = sanitize_text_field($options['smtp_encryption']);
            $phpmailer->Username   = sanitize_email($options['smtp_email']);
            $phpmailer->Password   = sanitize_text_field($options['smtp_password']);
            $phpmailer->From       = sanitize_email($options['smtp_email']); // Set the sender email to the SMTP email
            $phpmailer->FromName   = sanitize_text_field($options['smtp_sender_name']); // Set the sender name from settings
        }
    }
    add_action('phpmailer_init', 'stryker_smtp_send');
}

if (!function_exists('stryker_smtp_settings_page')) {
    function stryker_smtp_settings_page() {
        add_options_page(
            __('Stryker SMTP Settings', 'stryker-smtp'), 
            __('Stryker SMTP', 'stryker-smtp'), 
            'manage_options', 
            'stryker-smtp-settings', 
            'stryker_smtp_settings_html'
        );
    }
    add_action('admin_menu', 'stryker_smtp_settings_page');
}

if (!function_exists('stryker_smtp_send_test_email')) {
    function stryker_smtp_send_test_email() {
        if (isset($_POST['stryker_smtp_test_email'])) {
            check_admin_referer('stryker_smtp_send_test_email'); // Verify nonce for security
            
            if (isset($_POST['test_email'])) {
                $test_email = sanitize_email(wp_unslash($_POST['test_email']));
            
                if (is_email($test_email)) {
                    $subject = __('Stryker SMTP Test Email', 'stryker-smtp');
                    $message = __('This is a test email from Stryker SMTP Plugin.', 'stryker-smtp');
                    $headers = ['Content-Type: text/html; charset=UTF-8'];

                    $sent = wp_mail($test_email, $subject, $message, $headers);

                    if ($sent) {
                        echo '<div class="updated"><p>' . esc_html__('Test email sent successfully to ', 'stryker-smtp') . esc_html($test_email) . '.</p></div>';
                    } else {
                        echo '<div class="error"><p>' . esc_html__('Failed to send test email. Please check your SMTP settings.', 'stryker-smtp') . '</p></div>';
                    }
                } else {
                    echo '<div class="error"><p>' . esc_html__('Invalid email address.', 'stryker-smtp') . '</p></div>';
                }
            }
        }
    }
}

if (!function_exists('stryker_smtp_settings_html')) {
    function stryker_smtp_settings_html() {
        if (isset($_POST['submit'])) {
            check_admin_referer('stryker_smtp_save_settings'); // Verify nonce for security

            $smtp_email = isset($_POST['smtp_email']) ? sanitize_email(wp_unslash($_POST['smtp_email'])) : '';
            $smtp_password = isset($_POST['smtp_password']) ? sanitize_text_field(wp_unslash($_POST['smtp_password'])) : '';
            $smtp_host = isset($_POST['smtp_host']) ? sanitize_text_field(wp_unslash($_POST['smtp_host'])) : '';
            $smtp_port = isset($_POST['smtp_port']) ? intval($_POST['smtp_port']) : 465;
            $smtp_encryption = isset($_POST['smtp_encryption']) ? sanitize_text_field(wp_unslash($_POST['smtp_encryption'])) : 'ssl';
            $smtp_sender_name = isset($_POST['smtp_sender_name']) ? sanitize_text_field(wp_unslash($_POST['smtp_sender_name'])) : '';

            update_option('stryker_smtp_settings', [
                'smtp_email' => $smtp_email,
                'smtp_password' => $smtp_password,
                'smtp_host' => $smtp_host,
                'smtp_port' => $smtp_port,
                'smtp_encryption' => $smtp_encryption,
                'smtp_sender_name' => $smtp_sender_name,
            ]);

            echo '<div class="updated"><p>' . esc_html__('Settings saved.', 'stryker-smtp') . '</p></div>';
        }

        // Handle sending test email
        stryker_smtp_send_test_email();

        // Get the current settings
        $options = get_option('stryker_smtp_settings');
        ?>

        <div class="wrap">
            <h1><?php esc_html_e('Stryker SMTP Settings', 'stryker-smtp'); ?></h1>
            <form method="POST" action="">
                <?php wp_nonce_field('stryker_smtp_save_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="smtp_email"><?php esc_html_e('SMTP Email', 'stryker-smtp'); ?></label></th>
                        <td><input type="email" id="smtp_email" name="smtp_email" value="<?php echo esc_attr($options['smtp_email']); ?>" class="regular-text" required /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="smtp_password"><?php esc_html_e('SMTP Password', 'stryker-smtp'); ?></label></th>
                        <td><input type="password" id="smtp_password" name="smtp_password" value="<?php echo esc_attr($options['smtp_password']); ?>" class="regular-text" required /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="smtp_host"><?php esc_html_e('SMTP Host', 'stryker-smtp'); ?></label></th>
                        <td><input type="text" id="smtp_host" name="smtp_host" value="<?php echo esc_attr($options['smtp_host']); ?>" class="regular-text" required /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="smtp_port"><?php esc_html_e('SMTP Port', 'stryker-smtp'); ?></label></th>
                        <td><input type="number" id="smtp_port" name="smtp_port" value="<?php echo esc_attr($options['smtp_port']); ?>" class="regular-text" required /></td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="smtp_encryption"><?php esc_html_e('SMTP Encryption', 'stryker-smtp'); ?></label></th>
                        <td>
                            <select id="smtp_encryption" name="smtp_encryption" required>
                                <option value="ssl" <?php selected($options['smtp_encryption'], 'ssl'); ?>><?php esc_html_e('SSL', 'stryker-smtp'); ?></option>
                                <option value="tls" <?php selected($options['smtp_encryption'], 'tls'); ?>><?php esc_html_e('TLS', 'stryker-smtp'); ?></option>
                                <option value="none" <?php selected($options['smtp_encryption'], 'none'); ?>><?php esc_html_e('None', 'stryker-smtp'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><label for="smtp_sender_name"><?php esc_html_e('Sender Name', 'stryker-smtp'); ?></label></th>
                        <td><input type="text" id="smtp_sender_name" name="smtp_sender_name" value="<?php echo esc_attr($options['smtp_sender_name']); ?>" class="regular-text" required /></td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>

            <h2><?php esc_html_e('Send Test Email', 'stryker-smtp'); ?></h2>
            <form method="POST" action="">
                <?php wp_nonce_field('stryker_smtp_send_test_email'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="test_email"><?php esc_html_e('Test Email Address', 'stryker-smtp'); ?></label></th>
                        <td><input type="email" id="test_email" name="test_email" class="regular-text" required /></td>
                    </tr>
                </table>
                <input type="submit" name="stryker_smtp_test_email" class="button-primary" value="<?php esc_html_e('Send Test Email', 'stryker-smtp'); ?>" />
            </form>
        </div>

        <?php
    }
}

if (!function_exists('stryker_smtp_activate')) {
    function stryker_smtp_activate() {
        add_option('stryker_smtp_settings', [
            'smtp_email' => '',
            'smtp_password' => '',
            'smtp_host' => '',  
            'smtp_port' => 465, 
            'smtp_encryption' => 'ssl', 
            'smtp_sender_name' => get_bloginfo('name'), 
        ]);
    }
    register_activation_hook(__FILE__, 'stryker_smtp_activate');
}

if (!function_exists('stryker_smtp_deactivate')) {
    function stryker_smtp_deactivate() {
        delete_option('stryker_smtp_settings');
    }
    register_deactivation_hook(__FILE__, 'stryker_smtp_deactivate');
}
