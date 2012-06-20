<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seed Channel Model class
 *
 * @package         seed_ee_addon
 * @version         0.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */
class Seed_channel_model extends Seed_model {

	// --------------------------------------------------------------------
	// METHODS
	// --------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @access      public
	 * @return      void
	 */
	function __construct()
	{
		// Call parent constructor
		parent::__construct();

		// Initialize this model
		$this->initialize(
			'seed_example',
			'account_id',
			array(	
				'site_id'       		=> 'int(4) unsigned NOT NULL',
				'account_email'			=> 'varchar(100) NOT NULL',
				'currency'				=> 'varchar(100) NOT NULL',
				'return_success'		=> 'varchar(100) NOT NULL',
				'return_failure'		=> 'varchar(100) NOT NULL',
				'debug_mode'			=> 'int(1) unsigned NOT NULL',
				'status'				=> 'varchar(100) NOT NULL',
				'created_on'			=> 'int(10) unsigned NOT NULL')
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Installs given table
	 *
	 * @access      public
	 * @return      void
	 */
	public function install()
	{
		// Call parent install
		//parent::install();
	}


	// --------------------------------------------------------------

} // End class

/* End of file Seed_project_model.php */