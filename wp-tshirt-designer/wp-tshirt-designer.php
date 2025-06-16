<?php
/*
Plugin Name: WP T-Shirt Designer
Description: Prosty kreator koszulek dla WooCommerce z możliwością dodania obrazu i tekstu.
Version: 0.1.0
Author: Codex
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class WPTShirtDesigner {
    public function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        add_shortcode('tshirt_designer', [$this, 'render_designer']);
        add_action('wp_ajax_wptd_save_design', [$this, 'save_design']);
        add_action('wp_ajax_nopriv_wptd_save_design', [$this, 'save_design']);
    }

    public function enqueue_assets() {
        wp_enqueue_script('fabric', 'https://cdnjs.cloudflare.com/ajax/libs/fabric.js/4.6.0/fabric.min.js', [], null, true);
        wp_enqueue_script('wptd-designer', plugins_url('js/designer.js', __FILE__), ['fabric', 'jquery'], '0.1.0', true);
        wp_enqueue_style('wptd-designer', plugins_url('css/designer.css', __FILE__), [], '0.1.0');
        wp_localize_script('wptd-designer', 'wptd_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('wptd_nonce')
        ]);
    }

    public function render_designer() {
        ob_start();
        ?>
        <div id="wptd-container">
            <div id="wptd-canvas-wrapper">
                <canvas id="wptd-canvas" width="1181" height="1575"></canvas> <!-- 30x40 cm at 300 DPI -->
            </div>
            <div id="wptd-panel">
                <h3>Kreator Koszulki</h3>
                <input type="file" id="wptd-image-upload" accept="image/png" />
                <input type="text" id="wptd-text" placeholder="Dodaj tekst" />
                <select id="wptd-font">
                    <option value="Arial">Arial</option>
                    <option value="Courier New">Courier New</option>
                    <option value="Times New Roman">Times New Roman</option>
                </select>
                <button id="wptd-add-text">Dodaj tekst</button>
                <button id="wptd-add-to-cart">Dodaj do koszyka</button>
                <p id="wptd-price"></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function save_design() {
        check_ajax_referer('wptd_nonce', 'nonce');

        if (!isset($_POST['image'])) {
            wp_send_json_error('Brak obrazu.');
        }
        $upload_dir = wp_upload_dir();
        $data = $_POST['image'];
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = base64_decode($data);
        $filename = 'design-' . time() . '.png';
        $file = $upload_dir['path'] . '/' . $filename;
        file_put_contents($file, $data);

        $thumb = imagecreatetruecolor(150, 200);
        $src = imagecreatefrompng($file);
        imagecopyresampled($thumb, $src, 0, 0, 0, 0, 150, 200, imagesx($src), imagesy($src));
        $thumb_file = $upload_dir['path'] . '/thumb-' . $filename;
        imagepng($thumb, $thumb_file);
        imagedestroy($src);
        imagedestroy($thumb);

        $attachment = [
            'post_mime_type' => 'image/png',
            'post_title'     => sanitize_file_name($filename),
            'post_content'   => '',
            'post_status'    => 'inherit'
        ];
        $attach_id = wp_insert_attachment($attachment, $file);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file);
        wp_update_attachment_metadata($attach_id, $attach_data);

        wp_send_json_success(['url' => $upload_dir['url'] . '/' . $filename, 'thumb' => $upload_dir['url'] . '/thumb-' . $filename]);
    }
}

new WPTShirtDesigner();
?>
