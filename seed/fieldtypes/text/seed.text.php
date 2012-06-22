<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seed_fieldtype_text extends Seed_fieldtype
{
	public $title = 'text';
	
	public $settings = array(
		array(
			'name' 			=> 'from',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'count'			=> 'words',
			'description' 	=> 'Shortest length',
			'default'		=> 2
		),		
		array(
			'name' 			=> 'to',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'count'			=> 'words',
			'description' 	=> 'Longest length',
			'default' 		=> 6
		),
		array(
			'name' 			=> 'max',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'description' 	=> 'Max length',
			'default'		=> 126
		)
	);
}