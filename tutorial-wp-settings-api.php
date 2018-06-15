<?php
/*
Plugin Name: Tutorial WordPress Plugin
 */

function my_render_text() {
	$first_text = esc_attr(get_option('my_first_text', ''));

	echo '<h1>' . $first_text . '</h1>';
}
add_shortcode('my-settings', 'my_render_text');

function my_settings_page() {
	add_submenu_page(
		'options-general.php', // top level menu page
		'My Settings Page', // title of the settings page
		'My Settings', // title of the submenu
		'manage_options', // capability of the user to see this page
		'my-settings-page', // slug of the settings page
		'my_settings_page_html' // callback function to be called when rendering the page
	);
	add_action('admin_init', 'my_settings_init');
}
add_action('admin_menu', 'my_settings_page');

function my_settings_init() {
	add_settings_section(
		'my-settings-section', // id of the section
		'My Settings', // title to be displayed
		'', // callback function to be called when opening section
		'my-settings-page' // page on which to display the section, this should be the same as the slug used in add_submenu_page()
	);

	// register the setting
	register_setting(
		'my-settings-page', // option group
		'my_first_text'
	);

	add_settings_field(
		'my-first-text', // id of the settings field
		'My First Text', // title
		'my_settings_cb', // callback function
		'my-settings-page', // page on which settings display
		'my-settings-section' // section on which to show settings
	);
}

function my_settings_cb() {
	$first_text = esc_attr(get_option('my_first_text', ''));
	?>
    <div id="titlediv">
        <input id="title" type="text" name="my_first_text" value="<?php echo $first_text; ?>">
    </div>
    <?php
}

function my_settings_page_html() {
	// check user capabilities
	if (!current_user_can('manage_options')) {
		return;
	}
	?>

    <div class="wrap">
        <?php settings_errors();?>
        <form method="POST" action="options.php">
		    <?php settings_fields('my-settings-page');?>
		    <?php do_settings_sections('my-settings-page')?>
		    <?php submit_button();?>
        </form>
    </div>
    <?php
}