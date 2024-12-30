<?php
/**
 * @class CustomStoreLocator
 */
class CustomStoreLocator extends FLBuilderModule {
    /** 
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */  
    public function __construct()
    {
        parent::__construct(array(
            'name'          => __( 'Custom Store Locator', 'fl-builder'),
            'description'   => __( 'Custom Store Locator Module', 'fl-builder'),
            'category'		=> __('Custom Modules', 'fl-builder'),
            'dir'           => FL_MODULE_EXAMPLES_DIR . 'modules/custom-store-locator/',
            'url'           => FL_MODULE_EXAMPLES_URL . 'modules/custom-store-locator/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
        ));
        
        /** 
         * Use these methods to enqueue css and js already
         * registered or to register and enqueue your own.
         */
        // Already registered
        $this->add_css( 'custom-store-locator-css', $this->url . 'css/style.css');
        $this->add_js( 'custom-store-locator-js', $this->url . 'js/script.js', array('jquery'), '', true);
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
    public function get_data()
    {
		// Set up the query arguments
		$args = array(
			'post_type'      => 'store_locations_rec', // The custom post type
			'posts_per_page' => -1, // Get all posts
			'orderby'        => 'id', // Order by post title
			'order'          => 'ASC',  // Order ascending (A-Z)
		);

		// Execute the query
		$query = new WP_Query($args);

		// Prepare an array to hold the results
		$data = array();

		// Check if there are any posts
		if ($query->have_posts()) {
			// Loop through the posts and add to the array
			while ($query->have_posts()) {
				$query->the_post();

				// Add desired post data to the array
				// Example: Store post title and URL
				$data[] = array(
					'ID'    => get_the_ID(),
					'title' => get_the_title(),
					'url'   => get_the_permalink(),
					'type'	=> get_field('type'),
					'address' => get_field('address'),
					'formatted_address' => $this->format_address(get_field('address')),
					'search_keyword' => $this->search_address(get_field('address'))
				);
			}
		}

		// Reset post data after query
		wp_reset_postdata();

		// Return the results as an array
		return $data;
    }
	
	public function format_address($addressArray = array()) {
		if (!$addressArray) {
			return false;
		}
		
		$formattedAddress = sprintf(
			"%s %s, %s, %s %s, %s",
			$addressArray['street_number'],
			$addressArray['street_name'],
			$addressArray['city'],
			$addressArray['state_short'],
			$addressArray['post_code'],
			$addressArray['country']
		);
		
		return $formattedAddress;
	}
	
	// Search Address
	public function search_address($addressArray = array()) {
		if (!$addressArray) {
			return false;
		}
		
		$formattedAddress = sprintf(
			"%s %s, %s %s %s, %s, %s %s, %s, %s %s",
			$addressArray['address'],          // %s - Full address or main line
			$addressArray['name'],             // %s - Name of place (optional)
			$addressArray['street_number'],    // %s - Street number
			$addressArray['street_name'],      // %s - Full street name
			$addressArray['street_name_short'],// %s - Shortened street name
			$addressArray['city'],             // %s - City name
			$addressArray['state'],            // %s - Full state name
			$addressArray['state_short'],      // %s - Abbreviated state
			$addressArray['post_code'],        // %s - Postal code
			$addressArray['country'],          // %s - Full country name
			$addressArray['country_short']     // %s - Abbreviated country
		);

		return $formattedAddress;
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('CustomStoreLocator', array(
    'general'       => array( // Tab
        'title'         => __('General', 'fl-builder'), // Tab title
        'sections'      => array( // Tab Sections
            'general'       => array( // Section
                'title'         => __('Store Locator Settings', 'fl-builder'), // Section Title
                'fields'        => array( // Section Fields
                    'title'     => array(
                        'type'          => 'text',
                        'label'         => __('Title', 'fl-builder'),
                        'default'       => 'Custom Store Locator'
                    ),
					'gmap_api_key'     => array(
                        'type'          => 'text',
                        'label'         => __('Google Map API Key', 'fl-builder'),
                        'default'       => ''
                    ),
					'map_design'     => array(
                        'type'          => 'textarea',
                        'label'         => __('Map Design', 'fl-builder'),
                        'default'       => '',
						'help'          => 'Visit https://snazzymaps.com/explore for custom map styling.',
                    ),
					'map_design_url'     => array(
                        'type'          => 'text',
                        'label'         => __('Map Design URL', 'fl-builder'),
                        'default'       => 'https://snazzymaps.com/style/38/shades-of-grey'
                    ),
					'show_listings'   => array(
                        'type'          => 'select',
                        'label'         => __('Show Listings?', 'fl-builder'),
                        'default'       => 'yes',
                        'options'       => array(
                            'no'      => __('No', 'fl-builder'),
                            'yes'      => __('Yes', 'fl-builder')
                        )
                    ),
                )
            )
        )
    )
));