<?php
/*
Plugin Name: Custom Google Analytics
Description: Add Google Analytics code to the head of the site.
Version: 1.0
Author: Webzygo
*/

// Add menu in admin dashboard
function cga_add_menu() {
    add_menu_page(
        'Google Analytics Settings',
        'Google Analytics',
        'manage_options',
        'cga-settings',
        'cga_settings_page'
    );
}
add_action('admin_menu', 'cga_add_menu');

// Display settings page
function cga_settings_page() {
    ?>
    <div class="wrap">
        <h2>Google Analytics Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('cga_settings_group');
            do_settings_sections('cga-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Add settings and field to the settings page
function cga_settings_init() {
    register_setting('cga_settings_group', 'cga_tracking_code');

    add_settings_section(
        'cga_settings_section',
        'Google Analytics Tracking Code',
        'cga_settings_section_callback',
        'cga-settings'
    );

    add_settings_field(
        'cga_tracking_code',
        'Enter your Google Analytics tracking code:',
        'cga_tracking_code_callback',
        'cga-settings',
        'cga_settings_section'
    );
}
add_action('admin_init', 'cga_settings_init');

// Section callback function
function cga_settings_section_callback() {
    echo 'Enter your Google Analytics tracking code below:';
}

// Field callback function
function cga_tracking_code_callback() {
    $code = get_option('cga_tracking_code');
    ?>
    <input type="text" name="cga_tracking_code" value="<?php echo esc_attr($code); ?>" style="width: 300px;" />
    <p class="description">Add the gtag('config', 'YOUR-GA-CODE'); here.</p>
    <?php
}

// Add Google Analytics code to the head based on the entered code
function add_google_analytics_code() {
    $code = get_option('cga_tracking_code');
    if ($code) {
        ?>
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_html($code); ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() { dataLayer.push(arguments); }
            gtag('js', new Date());
            gtag('config', '<?php echo esc_html($code); ?>');
        </script>
        <?php
    }
}
add_action('wp_head', 'add_google_analytics_code');
