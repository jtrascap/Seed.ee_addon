<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// include config file
include PATH_THIRD.'seed/config'.EXT;

/**
 * Seed Update Class
 *
 * @package         seed_ee_addon
 * @version         1.0
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */
class Seed_upd {

	// --------------------------------------------------------------------
	// PROPERTIES
	// --------------------------------------------------------------------

	/**
	 * Version number
	 *
	 * @access      public
	 * @var         string
	 */
	public $version = SEED_VERSION;


	// --------------------------------------------------------------------
	// METHODS
	// --------------------------------------------------------------------

	/**
	 * Constructor: sets EE instance
	 *
	 * @access      public
	 * @return      null
	 */
	public function __construct()
	{

		// Set global object
		$this->EE =& get_instance();

		// Define the package path
		$this->EE->load->add_package_path(PATH_THIRD.'Seed');

		// Load libraries...
		$this->EE->load->library('Seed_model');

		// Load other models
		Seed_model::load_models();
	}

	// --------------------------------------------------------------------

	/**
	 * Install the module
	 *
	 * @access      public
	 * @return      bool
	 */
	public function install()
	{
		// --------------------------------------
		// Add row to modules table
		// --------------------------------------

		$this->EE->db->insert('modules', array(
			'module_name'    => SEED_CLASS_NAME,
			'module_version' => SEED_VERSION,
			'has_cp_backend' => 'y'
		));

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Uninstall the module
	 *
	 * @return	bool
	 */
	function uninstall()
	{
		// --------------------------------------
		// get module id
		// --------------------------------------

		$query = $this->EE->db->select('module_id')
		       ->from('modules')
		       ->where('module_name', SEED_CLASS_NAME)
		       ->get();


		// --------------------------------------
		// remove references from modules
		// --------------------------------------

		$this->EE->db->where('module_name', SEED_CLASS_NAME);
		$this->EE->db->delete('modules');

		return TRUE;
	}

	// --------------------------------------------------------------------

	/**
	 * Update the module
	 *
	 * @return	bool
	 */
	function update($current = '')
	{
		// --------------------------------------
		// Same Version - nothing to do
		// --------------------------------------

		if ($current == '' OR version_compare($current, SEED_VERSION) === 0)
		{
			return FALSE;
		}

		// Returning TRUE updates db version number
		return TRUE;
	}

	// --------------------------------------------------------------------

} // End class

/* End of file upd.Seed.php */