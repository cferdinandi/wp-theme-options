# WordPress Theme Options

WordPress Theme Options is a boilerplate for creating a theme options page. It includes templates for checkboxes, text inputs, select options, radio buttons, and text boxes.

This is not a plugin. A working knowledge of PHP and WordPress functions is required. Forked from [Michael Fields](https://gist.github.com/mfields/4678999).

[Download WordPress Theme Options](https://github.com/cferdinandi/wp-theme-options/archive/master.zip)

**In This Documentation**

1. [Getting Started](#getting-started)
2. [Creating Options Fields](#creating-options-fields)
3. [Creating the Page](#creating-the-page)
4. [Saving and Updating](#saving-and-updating)
5. [Using Options In Your Theme](#using-options-in-your-theme)
6. [Example](#example)
7. [How to Contribute](#how-to-contribute)
8. [License](#license)
9. [Changelog](#changelog)



## Getting Started

Copy `wp-theme-options.php` to your theme. Call the toolkit in your `functions.php` file:

```php
require_once('wp-theme-options.php');
```

Don't have a `functions.php` file? Create one, drop it in your theme directory, and add opening and closing PHP tags:

```php
<?php require_once('wp-theme-options.php'); ?>
```



## Creating Options Fields

Each option field requires its own uniquely named function that defines the layout of the field. These functions get called later when we create the menu page itself.

Select options and radio buttons also require an additional uniquely named function that contains an array of option or button choices.

### Examples

**Example Text Input:**

```php
// Create sample text input field
function kraken_settings_field_sample_text_input() {
	$options = kraken_get_theme_options();
	?>
	<input type="text" name="kraken_theme_options[sample_text_input]" id="sample-text-input" value="<?php echo esc_attr( $options['sample_text_input'] ); ?>" />
	<label class="description" for="sample-text-input">Sample text input</label>
	<?php
}
```

`kraken_get_theme_options()` is a function that's used to get a list of the current theme option settings.

**Example Radio Buttons:**

```php
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
```

### Creating Your Own

To create your own field, copy-and-paste one of the sample field functions, and replace all of the `sample_*` and `sample-*` strings with your own field name.



## Creating the Page

There are four functions that are used to create the actual theme options page in the admin menu:

`kraken_theme_options_init()` - Register's the theme options page and its fields.
`kraken_theme_options_render_page()` - Creates the theme options page layout.
`kraken_theme_options_add_page()` - Adds the theme options page to the admin menu.
`kraken_option_page_capability()` - Restricts access to the theme options page to admins only.

### Creating a section

By default, there's one unlabeled section on the theme options page (`general`). You can add additional sections by copy-and-pasting in this snippet of code:

```php
add_settings_section(
	'general', // Unique identifier for the settings section
	'', // Section title (we don't want one)
	'__return_false', // Section callback (we don't want anything)
	'theme_options' // Menu slug, used to uniquely identify the page
);
```

Replace `general` with your preferred section name, and add a section title if desired.

### Registering your fields

You need to register any field you create under in the `kraken_theme_options_init()` function. This tells WordPress to include that field in the theme options page.

```php
add_settings_field( $id, $title, $callback, $page, $section );
```

`$id` - The unique identifier for the field.
`$title` - The title for this field (will be shown next to field on the options page).
`$callback` - The function that creates the field (from the Theme Option Fields section).
`$page` - The menu page on which to display this field.
`$section` - The section of the options page in which to show the field.

For example, here's how you'd register the sample text box to the `general` section:

```php
add_settings_field( 'sample_textarea', 'Sample Textarea', 'kraken_settings_field_sample_textarea', 'theme_options', 'general' );
```

### Create the layout

The `kraken_theme_options_render_page()` is where you create the layout for the theme options page. If you want to add any additional instructions, that's the place to do it.

### Add the options page to the admin menu

The `kraken_theme_options_add_page()` function adds your theme options page to the admin menu. You don't need to change anything here, but if you wanted to have more than one theme options page, you would copy-and-paste this function and adjust the details as needed.

### Restricting access

The `kraken_option_page_capability()` function ensures that only people with admin privileges can access the theme options. You don't need to change anything here.



## Saving and Updating

There are two functions responsible for saving and updating theme options:

`kraken_get_theme_options()` - Get's the current theme options from the database. If none are set, applies defaults.
`kraken_theme_options_validate()` - Santizies and validates each theme option value to ensure no malicious code is added to the database.
Setting your defaults

You should specify a default value in the `kraken_get_theme_options()` function for any field you add to the theme options page.

```php
$defaults = array(
    'sample_checkbox'       => 'off',
    'sample_text_input'     => '',
    'sample_select_options' => '',
    'sample_radio_buttons'  => '',
    'sample_textarea'       => '',
);
```

### Sanitizing values

For security reasons, you should always santize values before adding them to the database to strip out any malicious code.

WordPress provides [a few hooks for santizing strings](http://ottopress.com/2010/wp-quickie-kses/). You may find it easiest to copy-and-paste the existing santizers and update the values as needed.



## Using Options In Your Theme

You can retrieve options for use in your theme using the `get_option($option)` function. Remember, we save our theme options to an array.

Here's how you would display the value of the sample text input:

```php
<?php echo get_option('kraken_theme_options[sample_text_input]'); ?>
```



## Example

Let's add a new text input to our theme options page for a Google Analytics ID.

If the field has a value, we'll add a Google Analytics script to our footer. Otherwise, we won't display anything.

### 1. Create an options field.

The first thing we'll need to do is create an options field for the Google Analytics ID.

```php
function kraken_settings_google_analytics() {
	$options = kraken_get_theme_options();
	?>
	<input type="text" name="kraken_theme_options[google_analytics]" id="google-analytics" value="<?php echo esc_attr( $options['google_analytics'] ); ?>" />
	<label class="description" for="google-analytics">Add your site's ID in this format: UA-XXXXX-X</label>
	<?php
}
```

### 2. Register the new field.

Next, we'll need to register the new field in the `kraken_theme_options_init()` function.

```php
add_settings_field( 'google_analytics', 'Google Analytics ID', 'kraken_settings_google_analytics', 'theme_options', 'general' );
```

### 3. Set the default value.

Set a default value for the field under the `kraken_get_theme_options()` function.

```php
$defaults = array(
	'google_analytics' => '',
);
```

### 4. Sanitize the value.

Add a sanitizer under the `kraken_theme_options_validate()` function.

```php
if ( isset( $input['google_analytics'] ) && ! empty( $input['google_analytics'] ) )
	$output['google_analytics'] = wp_filter_nohtml_kses( $input['google_analytics'] );
```

### 5. Use the value in your theme.

We'll create a function that gets the value of the Google Analytics ID field. If the field has a value, it will drop it into and return a Google Analytics script.

```php
function google_analytics() {

	$theme_settings = get_option('theme_settings');
	$analytics_id = $theme_settings['google_analytics'];
	$analytics = '';

	if ( $analytics_id != '' ) {
		$analytics = "
			<script>
				var _gaq=[['_setAccount','" . $analytics_id . "'],['_trackPageview']];
				(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
				g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
				s.parentNode.insertBefore(g,s)}(document,'script'));
			</script>";
	}

	return $analytics;
}
```

Now you can call this function in the footer.php template to add the Google Analytics script to the site.

```php
<?php echo google_analytics(); ?>
```



## How to Contribute

In lieu of a formal style guide, take care to maintain the existing coding style. Don't forget to update the version number, the changelog (in the `readme.md` file), and when applicable, the documentation.



## License

WordPress Theme Options is licensed under the [MIT License](http://gomakethings.com/mit/).



## Changelog

* v1.1 - December 13, 2013
	* Removed `screen_icon()` function, [deprecated in WP 3.8](http://codex.wordpress.org/Version_3.8).
* v1.0 - September 2, 2013
	* Initial release.