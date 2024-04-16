<?php
/**
 * Plugin Name:     Avif As Default Format
 * Plugin URI:      https://mpress.cc
 * Description:     Changes the default image format to .avif. Allows changing the output quality.
 * Author:          Mateusz Zadorożny
 * Author URI:      https://mpress.cc
 * Text Domain:     avif-as-default-format
 * Domain Path:     /languages
 * Version:         0.1.0
 * Requires Plugins: meta-box, meta-box-aio
 * Requires at least: 6.5
 * @package         Avif_As_Default_Format
 */

/** Create a settings page field */

function prefix_add_avif_quality_field()
{
    add_settings_field(
        'avif_quality', // ID of the settings field. We'll use it later in register_setting().
        'AVIF Quality', // Title of the field.
        'prefix_display_avif_quality_field', // Function that fills the field with the desired form inputs. The function should echo its output.
        'media', // The type of settings page on which to show the field.
        'default' // The section of the settings page in which to show the field.
    );
}
add_action('admin_init', 'prefix_add_avif_quality_field');

function prefix_display_avif_quality_field()
{
    // Get the current value of the option.
    $value = get_option('avif_quality');
    // HTML for the field.
    echo '<input type="number" id="avif_quality" name="avif_quality" value="' . esc_attr($value) . '" min="1" max="100" />';
}

function prefix_register_avif_quality_field()
{
    register_setting('media', 'avif_quality', 'prefix_validate_avif_quality');
}
add_action('admin_init', 'prefix_register_avif_quality_field');

function prefix_validate_avif_quality($input)
{
    // Check if the input is a number between 1 and 100.
    if (is_numeric($input) && $input >= 1 && $input <= 100) {
        // If the input is valid, return it.
        return $input;
    } else {
        // If the input is not valid, return an error.
        add_settings_error('avif_quality', 'avif_quality_error', 'AVIF Quality must be a number between 1 and 100.', 'error');
        return get_option('avif_quality');
    }
}

/** Use the value as avif quality */
function filter_avif_quality($quality, $mime_type)
{
    if ('image/avif' === $mime_type) {
        $avif_quality = get_option('avif_quality');
        if ($avif_quality) {
            return $avif_quality;
        } else {
            return 75;
        }
    }
    return $quality;
}
add_filter('wp_editor_set_quality', 'filter_avif_quality', 10, 2);

// Output AVIFs for uploaded JPEGs
function filter_image_editor_output_format($formats)
{
    $formats['image/jpeg'] = 'image/avif';
    return $formats;
}
add_filter('image_editor_output_format', 'filter_image_editor_output_format');

function prefix_plugin_activation()
{
    // Set an option that holds whether to redirect or not.
    update_option('prefix_do_activation_redirect', true);
}
register_activation_hook(__FILE__, 'prefix_plugin_activation');

function prefix_redirect_after_activation()
{
    // Check if the redirect option is set.
    if (get_option('prefix_do_activation_redirect', false)) {
        // Redirect to your Media Settings page.
        wp_redirect(admin_url('options-media.php'));
        // Delete the redirect option to not redirect on the next load.
        delete_option('prefix_do_activation_redirect');
        exit;
    }
}
add_action('admin_init', 'prefix_redirect_after_activation');
