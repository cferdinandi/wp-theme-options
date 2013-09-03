<?php

/* ======================================================================

    Theme Options v1.0
    Adjust theme settings from the admin dashboard, by Michael Fields.
    https://gist.github.com/mfields/4678999
    
    Forked by Chris Ferdinandi.
    http://gomakethings.com

    Free to use under the MIT License.
    http://gomakethings.com/mit/
    
 * ====================================================================== */


/* ======================================================================
    THEME OPTION FIELDS
    Create the theme option fields.

    Each option field requires its own uniquely named function.
    Select options and radio buttons also require an additional 
    uniquely named function with an array of option choices.
 * ====================================================================== */

// Create sample checkbox field
function kraken_settings_field_sample_checkbox() {
    $options = kraken_get_theme_options();
    ?>
    <label for="sample-checkbox">
        <input type="checkbox" name="kraken_theme_options[sample_checkbox]" id="sample-checkbox" <?php checked( 'on', $options['sample_checkbox'] ); ?> />
        A sample checkbox.
    </label>
    <?php
}



// Create sample text input field
function kraken_settings_field_sample_text_input() {
    $options = kraken_get_theme_options();
    ?>
    <input type="text" name="kraken_theme_options[sample_text_input]" id="sample-text-input" value="<?php echo esc_attr( $options['sample_text_input'] ); ?>" />
    <label class="description" for="sample-text-input">Sample text input</label>
    <?php
}



// Create options for select options field
// Used in kraken_settings_field_sample_select_options()
function kraken_sample_select_option_choices() {
    $sample_select_options = array(
        '0' => array(
            'value' => '0',
            'label' => 'Zero'
        ),
        '1' => array(
            'value' => '1',
            'label' => 'One'
        ),
        '2' => array(
            'value' => '2',
            'label' => 'Two'
        ),
        '3' => array(
            'value' => '3',
            'label' => 'Three'
        ),
        '4' => array(
            'value' => '4',
            'label' => 'Four'
        ),
        '5' => array(
            'value' => '5',
            'label' => 'Five'
        )
    );

    return apply_filters( 'kraken_sample_select_option_choices', $sample_select_options );
}

// Create sample select options field
function kraken_settings_field_sample_select_options() {
    $options = kraken_get_theme_options();
    ?>
    <select name="kraken_theme_options[sample_select_options]" id="sample-select-options">
        <?php
            $selected = $options['sample_select_options'];
            $p = '';
            $r = '';

            foreach ( kraken_sample_select_option_choices() as $option ) {
                $label = $option['label'];
                if ( $selected == $option['value'] ) // Make default first in list
                    $p = "\n\t<option style=\"padding-right: 10px;\" selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
                else
                    $r .= "\n\t<option style=\"padding-right: 10px;\" value='" . esc_attr( $option['value'] ) . "'>$label</option>";
            }
            echo $p . $r;
        ?>
    </select>
    <label class="description" for="sample_theme_options[selectinput]">Sample select input</label>
    <?php
}



// Create options for radio buttons field
// Used in kraken_settings_field_sample_radio_buttons()
function kraken_sample_radio_button_choices() {
    $sample_radio_buttons = array(
        'yes' => array(
            'value' => 'yes',
            'label' => 'Yes'
        ),
        'no' => array(
            'value' => 'no',
            'label' => 'No'
        ),
        'maybe' => array(
            'value' => 'maybe',
            'label' => 'Maybe'
        )
    );

    return apply_filters( 'kraken_sample_radio_button_choices', $sample_radio_buttons );
}

// Create sample radio buttons field
function kraken_settings_field_sample_radio_buttons() {
    $options = kraken_get_theme_options();

    foreach ( kraken_sample_radio_button_choices() as $button ) {
    ?>
    <div class="layout">
        <label class="description">
            <input type="radio" name="kraken_theme_options[sample_radio_buttons]" value="<?php echo esc_attr( $button['value'] ); ?>" <?php checked( $options['sample_radio_buttons'], $button['value'] ); ?> />
            <?php echo $button['label']; ?>
        </label>
    </div>
    <?php
    }
}



// Create sample textarea field
function kraken_settings_field_sample_textarea() {
    $options = kraken_get_theme_options();
    ?>
    <textarea class="large-text" type="text" name="kraken_theme_options[sample_textarea]" id="sample-textarea" cols="50" rows="10" /><?php echo esc_textarea( $options['sample_textarea'] ); ?></textarea>
    <label class="description" for="sample-textarea">Sample textarea</label>
    <?php
}





/* ======================================================================
    THEME OPTIONS MENU
    Create the theme options menu.

    Each option field requires its own add_settings_field function.
 * ====================================================================== */

// Register the theme options page and its fields
function kraken_theme_options_init() {
    register_setting(
        'kraken_options', // Options group, see settings_fields() call in kraken_theme_options_render_page()
        'kraken_theme_options', // Database option, see kraken_get_theme_options()
        'kraken_theme_options_validate' // The sanitization callback, see kraken_theme_options_validate()
    );

    // Register our settings field group
    add_settings_section(
        'general', // Unique identifier for the settings section
        '', // Section title (we don't want one)
        '__return_false', // Section callback (we don't want anything)
        'theme_options' // Menu slug, used to uniquely identify the page; see kraken_theme_options_add_page()
    );

    // Register our individual settings fields
    // add_settings_field( $id, $title, $callback, $page, $section );
    // $id - Unique identifier for the field.
    // $title - Setting field title.
    // $callback - Function that creates the field (from the Theme Option Fields section).
    // $page - The menu page on which to display this field.
    // $section - The section of the settings page in which to show the field.

    add_settings_field( 'sample_checkbox', 'Sample Checkbox', 'kraken_settings_field_sample_checkbox', 'theme_options', 'general' );
    add_settings_field( 'sample_text_input', 'Sample Text Input', 'kraken_settings_field_sample_text_input', 'theme_options', 'general' );
    add_settings_field( 'sample_select_options', 'Sample Select Options', 'kraken_settings_field_sample_select_options', 'theme_options', 'general' );
    add_settings_field( 'sample_radio_buttons', 'Sample Radio Buttons', 'kraken_settings_field_sample_radio_buttons', 'theme_options', 'general' );
    add_settings_field( 'sample_textarea', 'Sample Textarea', 'kraken_settings_field_sample_textarea', 'theme_options', 'general' );
}
add_action( 'admin_init', 'kraken_theme_options_init' );



// Create theme options menu
// The content that's rendered on the menu page.
function kraken_theme_options_render_page() {
    ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
        <h2><?php printf( '%s Theme Options', $theme_name ); ?></h2>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php
                settings_fields( 'kraken_options' );
                do_settings_sections( 'theme_options' );
                submit_button();
            ?>
        </form>
    </div>
    <?php
}



// Add the theme options page to the admin menu
function kraken_theme_options_add_page() {
    $theme_page = add_theme_page(
        'Theme Options', // Name of page
        'Theme Options', // Label in menu
        'edit_theme_options', // Capability required
        'theme_options', // Menu slug, used to uniquely identify the page
        'kraken_theme_options_render_page' // Function that renders the options page
    );
}
add_action( 'admin_menu', 'kraken_theme_options_add_page' );



// Restrict access to the theme options page to admins
function kraken_option_page_capability( $capability ) {
    return 'edit_theme_options';
}
add_filter( 'option_page_capability_kraken_options', 'kraken_option_page_capability' );







/* ======================================================================
    PROCESS THEME OPTIONS
    Process and save updates to the theme options.

    Each option field requires a default value under kraken_get_theme_options(),
    and an if statement under kraken_theme_options_validate();
 * ====================================================================== */

// Get the current options from the database.
// If none are specified, use these defaults.
function kraken_get_theme_options() {
    $saved = (array) get_option( 'kraken_theme_options' );
    $defaults = array(
        'sample_checkbox'       => 'off',
        'sample_text_input'     => '',
        'sample_select_options' => '',
        'sample_radio_buttons'  => '',
        'sample_textarea'       => '',
    );

    $defaults = apply_filters( 'kraken_default_theme_options', $defaults );

    $options = wp_parse_args( $saved, $defaults );
    $options = array_intersect_key( $options, $defaults );

    return $options;
}



// Sanitize and validate updated theme options
function kraken_theme_options_validate( $input ) {
    $output = array();

    // Checkboxes will only be present if checked.
    if ( isset( $input['sample_checkbox'] ) )
        $output['sample_checkbox'] = 'on';

    // The sample text input must be safe text with no HTML tags
    if ( isset( $input['sample_text_input'] ) && ! empty( $input['sample_text_input'] ) )
        $output['sample_text_input'] = wp_filter_nohtml_kses( $input['sample_text_input'] );

    // The sample select option must actually be in the array of select options
    if ( isset( $input['sample_select_options'] ) && array_key_exists( $input['sample_select_options'], kraken_sample_select_option_choices() ) )
        $output['sample_select_options'] = $input['sample_select_options'];

    // The sample radio button value must be in our array of radio button values
    if ( isset( $input['sample_radio_buttons'] ) && array_key_exists( $input['sample_radio_buttons'], kraken_sample_radio_button_choices() ) )
        $output['sample_radio_buttons'] = $input['sample_radio_buttons'];

    // The sample textarea must be safe text with the allowed tags for posts
    if ( isset( $input['sample_textarea'] ) && ! empty( $input['sample_textarea'] ) )
        $output['sample_textarea'] = wp_filter_post_kses( $input['sample_textarea'] );

    return apply_filters( 'kraken_theme_options_validate', $output, $input );
}

?>