<?php
/**
 * @class CustomTestimonialSlider
 */
class CustomTestimonialSlider extends FLBuilderModule {
    /** 
     * Constructor function for the module. You must pass the
     * name, description, dir and url in an array to the parent class.
     *
     * @method __construct
     */  
    public function __construct()
    {
        parent::__construct(array(
            'name'          => __( 'Custom Testimonial Slider', 'fl-builder'),
            'description'   => __( 'Custom Testimonial Slider Module', 'fl-builder'),
            'category'		=> __('Custom Modules', 'fl-builder'),
            'dir'           => FL_MODULE_EXAMPLES_DIR . 'modules/custom-testimonial-slider/',
            'url'           => FL_MODULE_EXAMPLES_URL . 'modules/custom-testimonial-slider/',
            'editor_export' => true, // Defaults to true and can be omitted.
            'enabled'       => true, // Defaults to true and can be omitted.
        ));
        
        /** 
         * Use these methods to enqueue css and js already
         * registered or to register and enqueue your own.
         */
        // Already registered
        $this->add_css( 'custom-testimonial-slider-css', $this->url . 'css/style.css');
        $this->add_js( 'custom-testimonial-slider-js', $this->url . 'js/script.js', array('jquery'), '', true);
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
    public function example_method()
    {
    
    }
}
/**
 * Register the module and its form settings.
 */
FLBuilder::register_module('CustomTestimonialSlider', array(
    'general'       => array( // Tab
        'title'         => __('General', 'fl-builder'), // Tab title
        'sections'      => array( // Tab Sections
            'general'       => array( // Section
                'title'         => __('Slider Settings', 'fl-builder'), // Section Title
                'fields'        => array( // Section Fields
                    'title'     => array(
                        'type'          => 'text',
                        'label'         => __('Title', 'fl-builder'),
                        'default'       => 'Testimonial Slider'
                    ),
                )
            )
        )
    )
));