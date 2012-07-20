<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Seed MCP File 
 *
 * @package         seed_ee_addon
 * @version         0.9.4
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */

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


		$controls = array();

		$this->EE->cp->set_right_nav( $controls );


		// Load helper
		$this->EE->load->helper('Seed');
		
		// Load Seed base model
		$this->EE->load->library('Seed_model');

		// Load other models  
		Seed_model::load_models();		
	}
	// --------------------------------------------------------------------

	
	// --------------------------------------------------------------------

	/**
	 * New Seed page
	 *
	 * @access      public
	 * @return      string
	 */

	function index( $type = 'new', $message = array() )
	{	

		// --------------------------------------
		// Load some libraries
		// --------------------------------------

		$this->EE->load->library('javascript');

		// --------------------------------------
		// Load assets
		// --------------------------------------
		$this->_add_morphine();
		$this->EE->cp->load_package_css('seed'.$this->nocache);
		//$this->EE->cp->load_package_js('seed'.$this->nocache);


		$this->EE->cp->set_variable('cp_page_title', lang('seed_new_seed'));

		// Get the channel list


		$this->data['channels'] = array();

		// --------------------------------------
		// Get channels and searchable fields
		// --------------------------------------

		$results  = $this->EE->db->select('c.channel_id, c.channel_title, f.*')
					->from('channels c')
					->join('channel_fields f', 'c.field_group = f.group_id', 'left')
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

			if( $row['field_id'] == '' ) continue;

			if( $row['field_type'] == 'matrix' )
			{
				$this->EE->seed_channel_model->get_plugin( 'matrix' );
				// Decode the settings
				$settings = unserialize( base64_decode( $row['field_settings'] ) );

				$row['cells'] = $this->EE->seed_plugins->$row['field_type']->get_cell_types( $settings );

				// Now unset that plugin so we're clean for later
				unset( $this->EE->seed_plugins );
			}

			// Add custom fields to this channel
			$this->data['channels'][$row['channel_id']]['fields'][$row['field_id']] = $row;
		}

		if( $type == 'error' )
		{
			$this->data['errors'] = $message;
		}


		if( $type == 'success' )
		{
			$this->data['success'] = $message;
		}
		
		$this->data[ 'type' ] = $type;

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
		if( !empty( $errors ) ) 
		{
			return $this->index( 'error', $errors );
		}

		if( $seed_count <= 0 ) 
		{
			$errors[] = lang('seed_error_count_not_positive');
		}

		if( !empty( $errors ) ) 
		{
			return $this->index( 'error', $errors );
		}

		// Basic checks in place. Throw this over to the seed model for the actual grunt work
		$return = $this->EE->seed_channel_model->seed();

		if( is_array( $return ) )
		{
			return $this->index( 'error', $return );
		}

		// Get the basic details for the channel

		$channel  = $this->EE->db->select('c.channel_id, c.channel_title')
					->from('channels c')
					->where('c.channel_id', $channel_id)
					->get()
					->row_array();

		$ret[] = str_replace( 
			 		array(	'%seed_count%',
			 				'%channel_name%', 
			 				'%channel_link%' ), 
			 		array( 	$seed_count,
			 				$channel['channel_title'],
			 				str_replace( '&amp;D=', '&D=', BASE.'&C=content_edit&channel_id='.$channel_id ) ),
			 		lang('seed_success_message'));

		return $this->index( 'success', $ret );

	}
	// --------------------------------------------------------------------

	/**
	 * Module Settings page
	 *
	 * @access      public
	 * @return      string
	 */
	public function settings()
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


	private function _add_morphine()
	{
		$theme_folder_url = $this->EE->config->item('theme_folder_url');

		if (substr($theme_folder_url, -1) != '/') {
			$theme_folder_url .= '/';
		}

		$theme_folder_url .= "third_party/seed/";

		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.$theme_folder_url.'styles/screen.css" />');

		$this->EE->cp->add_to_head('<script type="text/javascript" charset="utf-8" src="'.$theme_folder_url.'scripts/compressed.js"></script>');
		$this->EE->cp->add_to_head('<script type="text/javascript" charset="utf-8" src="'.$theme_folder_url.'seed.js"></script>');


		// Add datepicker


		$date_fmt = $this->EE->session->userdata('time_format');
		$date_fmt = $date_fmt ? $date_fmt : $this->EE->config->item('time_format');

		$this->EE->cp->add_to_head('<style type="text/css">.hasDatepicker{background:#fff url('.$this->EE->config->item('theme_folder_url').'cp_themes/default/images/calendar_bg.gif) no-repeat 98% 2px;background-repeat:no-repeat;background-position:99%;}</style>');
		$this->EE->cp->add_to_head( trim('
			<script type="text/javascript">
				$.createDatepickerTime=function(){
					date = new Date();
					hours = date.getHours();
					minutes = date.getMinutes();
					suffix = "";
					format = "'.$date_fmt.'";
				
					if (minutes < 10) {
						minutes = "0" + minutes;
					}
				
					if (format == "us") {
						if (hours > 12) {
							hours -= 12;
							suffix = " PM";
						} else if (hours == 12) {
							suffix = " PM";
						} else {
							suffix = " AM";
						}
					}
				
					return " \'" + hours + ":" + minutes + suffix + "\'";
				}
			
				EE.date_obj_time = $.createDatepickerTime();
			</script>') );


		// Set up date js
		$this->EE->javascript->output('
			$("#entry_date").datepicker({dateFormat: $.datepicker.W3C + date_obj_time, defaultDate: new Date('.( $this->EE->localize->set_localized_time( $this->EE->localize->now * 1000).')});
		') );


		$this->EE->cp->add_js_script(array(
			'ui'		=> 'datepicker'
		));

		$this->EE->javascript->compile();
		

	}




	
}
