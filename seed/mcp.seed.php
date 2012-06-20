<?php defined('BASEPATH') or exit('No direct script access allowed');


class Seed_mcp
{
	public $module_name;
	private $nocache;
	private $data;

	function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
		$this->module_name = strtolower(str_replace('_mcp', '', get_class($this)));
		$this->base = str_replace( '&amp;D=', '&D=', BASE.'&C=addons_modules&M=show_module_cp&module=' . $this->module_name );


		$this->data['base_url'] = $this->base;
		$this->controls[]  = $this->base.AMP.'method=settings';


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
	// --------------------------------------------------------------------

	/**
	 * Module Home page
	 *
	 * @access      public
	 * @return      string
	 */

	function index()
	{	
		$this->EE->cp->set_variable('cp_page_title', lang('seed_module_name'));

		$this->data['new_seed_url'] = $this->base . '&method=seed';

		return $this->EE->load->view('mcp_index', $this->data, TRUE);
	
	}
	// --------------------------------------------------------------------

	/**
	 * New Seed page
	 *
	 * @access      public
	 * @return      string
	 */

	function seed( $errors = array() )
	{	

		// --------------------------------------
		// Load some libraries
		// --------------------------------------

		$this->EE->load->library('javascript');

		// --------------------------------------
		// Load assets
		// --------------------------------------

		//$this->EE->cp->load_package_css('seed'.$this->nocache);
		$this->EE->cp->load_package_js('seed'.$this->nocache);


		$this->EE->cp->set_variable('cp_page_title', lang('seed_new_seed'));

		// Get the channel list


		$this->data['channels'] = array();

		// --------------------------------------
		// Get channels and searchable fields
		// --------------------------------------

		$results  = $this->EE->db->select('c.channel_id, c.channel_title, f.*')
					->from('channels c')
					->join('channel_fields f', 'c.field_group = f.group_id')
					->where('c.site_id', '1')
			       	->order_by('c.channel_title', 'asc')
			       	->order_by('f.field_order', 'asc')
					->get()
					->result_array();

		
		foreach( $results as $row )
		{
			// Remember channel title
			$this->data['channels'][$row['channel_id']]['title'] = $row['channel_title'];

			// Add 'Title' to fields while we're here
			if ( ! isset($this->data['channels'][$row['channel_id']]['fields']))
			{
				$this->data['channels'][$row['channel_id']]['fields'][0] = array('field_label'=>lang('title'), 'field_name'=>'title', 'is_title' => TRUE, 'field_required'=>'y', 'field_maxl' => '255', 'field_type' => 'text' );
			}

			// Add custom fields to this channel
			$this->data['channels'][$row['channel_id']]['fields'][$row['field_id']] = $row;
		}

		//die('<prE>'.print_R($this->data,1));

		$this->data['errors'] = $errors;

		return $this->EE->load->view('mcp_seed', $this->data, TRUE);
	
	}

	// --------------------------------------------------------------------

	/**
	 * Start Seed
	 *
	 * @access      public
	 * @return      void
	 */
	function start_seed()
	{
		// Check we've got a passed channel_id and seed count
		$channel_id = $this->EE->input->post('seed_channel');
		$seed_count = $this->EE->input->post('seed_count');

		$errors = array();


		if( $channel_id == '' ) 
		{
			$errors[] = lang('seed_error_no_channel');
		}
		if( $seed_count == '' ) 
		{
			$errors[] = lang('seed_error_no_count');
		}
		if( !empty( $errors ) ) return $this->seed( $errors );

		if( $seed_count <= 0 ) 
		{
			$errors[] = lang('seed_error_count_not_positive');
		}

		if( !empty( $errors ) ) return $this->seed( $errors );


		// Basic checks in place. Throw this over to the seed model for the actual grunt work
		$return = $this->EE->seed_channel_model->seed();

		return;

	}
	// --------------------------------------------------------------------

	/**
	 * Module Settings page
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
