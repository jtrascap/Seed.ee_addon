<?php defined('BASEPATH') or exit('No direct script access allowed');


class Seed_mcp
{
	public $module_name;
	private $nocache;


	function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
		$this->module_name = strtolower(str_replace('_mcp', '', get_class($this)));
		$this->base = str_replace( '&amp;D=', '&D=', BASE.'&C=addons_modules&M=show_module_cp&module=' . $this->module_name );


		$this->contols[]  = $this->base.AMP.'method=settings';


		$controls = array(  lang('seed')		=> $this->base . '&method=index',
							lang('settings')	=> $this->base . '&method=settings');

		$this->EE->cp->set_right_nav( $controls );


		// Load helper
		$this->EE->load->helper('Seed');
		
		// Load Seed base model
		$this->EE->load->library('Seed_model');

		// Load other models  
		Seed_model::load_models();		
	}


	function index()
	{	
		$this->EE->cp->set_variable('cp_page_title', lang('seed_module_name'));

		return $this->EE->load->view('index', array(), TRUE);
	
	}
	// --------------------------------------------------------------------

	/**
	 * Module home page
	 *
	 * @access      public
	 * @return      string
	 */
	function settings()
	{
		// --------------------------------------
		// Load some libraries
		// --------------------------------------

		$this->EE->load->library('javascript');

		$this->EE->cp->set_variable('cp_page_title', lang('settings'));
		$this->EE->cp->set_breadcrumb($this->base, lang('seed_module_name'));

		$this->cached_vars['form_post_url'] = $this->base . '&method=save_settings';

		return $this->EE->load->view('settings', $this->cached_vars, TRUE);
	}





	public function save_settings()
	{
		$data = array();

		foreach( $this->EE->seed_example_model->attributes() as $attribute )
		{
			if( $this->EE->input->get_post( $attribute ) != '' )
			{
				$data[ $attribute ] = $this->EE->input->get_post( $attribute );
			}
		}

		$this->EE->seed_example_model->insert( $data );

        // ----------------------------------
        //  Redirect to Settings page with Message
        // ----------------------------------
        
        $this->EE->functions->redirect($this->base . '&method=settings&msg=preferences_updated');
        exit;

	}




	
}
