<?php
/**
 * @class CustomClassesTab
 */
class CustomClassesTab extends FLBuilderModule {
    /** 
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */  
    public function __construct()
    {
        parent::__construct(array(
            'name'          => __( 'Custom Classes Tab', 'fl-builder'),
            'description'   => __( 'Custom Classes Tab Module', 'fl-builder'),
            'category'		=> __('Custom Modules', 'fl-builder'),
            'dir'           => FL_MODULE_EXAMPLES_DIR . 'modules/custom-classes-tab/',
            'url'           => FL_MODULE_EXAMPLES_URL . 'modules/custom-classes-tab/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
        ));
        
        /** 
         * Use these methods to enqueue css and js already
         * registered or to register and enqueue your own.
         */
        // Already registered
        $this->add_css( 'custom-classes-tab-css', $this->url . 'css/style.css');
        $this->add_js( 'custom-classes-tab-js', $this->url . 'js/script.js', array('jquery'), '', true);
    }
    /** 
     * Use this method to work with settings data before 
     * it is saved. You must return the settings object. 
     *
     * @method update
     * @param $settings {object}
     */      
    public function update($settings)
    {   
        return $settings;
    }
    /** 
     * This method will be called by the builder
     * right before the module is deleted. 
     *
     * @method delete
     */      
    public function delete()
    {
    
    }
    /** 
     * Add additional methods to this class for use in the 
     * other module files such as preview.php, frontend.php
     * and frontend.css.php.
     * 
     *
     * @method example_method
     */   
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('CustomClassesTab', array(
    'general'       => array( // Tab
        'title'         => __('General', 'fl-builder'), // Tab title
        'sections'      => array( // Tab Sections
            'general'       => array( // Section
                'title'         => __('Custom Classes Tab Settings', 'fl-builder'), // Section Title
                'fields'        => array( // Section Fields
                    'category'   => array(
                        'type'          => 'select',
                        'label'         => __('Select Category', 'fl-builder'),
                        'default'       => custom_classes_tab_categories_default(),
                        'options'       => custom_classes_tab_categories()
                    ),
					'theme_color'   => array(
                        'type'          => 'select',
                        'label'         => __('Select Theme Color', 'fl-builder'),
                        'default'       => 'yellow',
                        'options'       => array(
                            'yellow'      => __('Yellow', 'fl-builder'),
                            'green'      => __('Green', 'fl-builder'),
                            'purple'      => __('Purple', 'fl-builder')
                        )
                    ),
					'theme_content_color'   => array(
                        'type'          => 'select',
                        'label'         => __('Select Theme Content Color', 'fl-builder'),
                        'default'       => 'light',
                        'options'       => array(
                            'light'      => __('Light', 'fl-builder'),
                            'dark'      => __('Dark', 'fl-builder'),
                        )
                    ),
                )
            )
        )
    )
));

function separateToneStrong($text) {
    // Check if the string contains 'tone'
    if (stripos($text, 'tone') !== false) {
        // Split the string at 'tone'
        $parts = preg_split('/(tone)/i', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
        
        // Construct the HTML output
        return $parts[1] . "<span class='sep-text'>" . $parts[2] . "</span>";
    }

    // If 'tone' is not in the string, return the original text
    return $text;
}

function custom_classes_tab_category($category_id) {
    // Define the query arguments
    $args = array(
        'post_type'      => 'classes_data', // Custom post type
        'posts_per_page' => -1,            // Retrieve all posts
        'tax_query'      => array(
            array(
                'taxonomy' => 'classes_cat', // Custom taxonomy
                'field'    => 'term_id',       // Use slug to match category
                'terms'    => $category_id, // The category slug to filter by
            ),
        ),
		'order' => 'ASC'
    );

    // Create the query
    $query = new WP_Query($args);

    // Check if any posts are found
    if ($query->have_posts()) {
        $posts = array();

        // Loop through posts
        while ($query->have_posts()) {
            $query->the_post();
			
			// Get post thumbnail ID
			$thumbnail_id = get_post_thumbnail_id(get_the_ID());
			$thumbnail_url = '';

			// Get the image URL for the 'large' size, if available
			if ($thumbnail_id) {
				$thumbnail = wp_get_attachment_image_src($thumbnail_id, 'large');
				$thumbnail_url = $thumbnail ? $thumbnail[0] : '';
			}
			
            $posts[] = array(
                'id'    => get_the_ID(),
                'title' => get_the_title(),
                'link'  => get_permalink(),
				'image' => $thumbnail_url,
				'content' => get_the_content(),
				'tab_menu_title' => get_field('tab_menu_title')
            );
        }

        // Reset post data
        wp_reset_postdata();

        return $posts;
    }

    // No posts found
    return array();
}

function custom_classes_tab_categories() {
    // Fetch all terms from the 'classes_cat' taxonomy
    $categories = get_terms(array(
        'taxonomy'   => 'classes_cat',
        'hide_empty' => false, // Include empty categories
    ));

    // Initialize an empty array for the options
    $options = array();

    // Check if terms are available and not an error
    if (!is_wp_error($categories) && !empty($categories)) {
        foreach ($categories as $category) {
            // Use term_id as the key and the name as the value
            $options[$category->term_id] = __($category->name, 'fl-builder');
        }
    }

    return $options;
}

function custom_classes_tab_categories_default() {
    // Fetch all categories using the custom function
    $categories = custom_classes_tab_categories();

    // Check if categories exist and return the first one
    if (!empty($categories) && is_array($categories)) {
        // Get the first key-value pair
        reset($categories); // Reset pointer to the first element
        $first_key = key($categories); // Get the first key
        $first_value = current($categories); // Get the first value

        // return array($first_key => $first_value); // Return as a key-value pair
        return $first_key;
    }

    // Return an empty value if no categories are found
    return '';
}