<?php
/**
 * @class CustomMap
 */
class CustomMap extends FLBuilderModule {
    /** 
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */  
    public function __construct()
    {
        parent::__construct(array(
            'name'          => __( 'Custom Map', 'fl-builder'),
            'description'   => __( 'Custom Map Module', 'fl-builder'),
            'category'		=> __('Custom Modules', 'fl-builder'),
            'dir'           => FL_MODULE_EXAMPLES_DIR . 'modules/custom-map/',
            'url'           => FL_MODULE_EXAMPLES_URL . 'modules/custom-map/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
        ));
        
        /** 
         * Use these methods to enqueue css and js already
         * registered or to register and enqueue your own.
         */
        // Already registered
        $this->add_css( 'custom-map-css', $this->url . 'css/style.css');
        $this->add_js( 'custom-map-js', $this->url . 'js/script.js', array('jquery'), '', true);
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
FLBuilder::register_module('CustomMap', array(
    'general'       => array( // Tab
        'title'         => __('General', 'fl-builder'), // Tab title
        'sections'      => array( // Tab Sections
            'general'       => array( // Section
                'title'         => __('Store Locator Settings', 'fl-builder'), // Section Title
                'fields'        => array( // Section Fields
                    'latitude'     => array(
                        'type'          => 'text',
                        'label'         => __('Latitude', 'fl-builder'),
                        'default'       => ''
                    ),
					'longitude'     => array(
                        'type'          => 'text',
                        'label'         => __('Longitude', 'fl-builder'),
                        'default'       => ''
                    ),
                    'map_height'     => array(
                        'type'          => 'text',
                        'label'         => __('Map Height', 'fl-builder'),
                        'default'       => ''
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
                        'default'       => ''
                    ),
					'map_title'     => array(
                        'type'          => 'text',
                        'label'         => __('Map Title', 'fl-builder')
                    ),
					'map_info_window'     => array(
                        'type'          => 'textarea',
                        'label'         => __('Map Info Window', 'fl-builder')
                    ),
                )
            )
        )
    )
));