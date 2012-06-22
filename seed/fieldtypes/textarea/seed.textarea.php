<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seed_fieldtype_textarea extends Seed_fieldtype
{
	public $title = 'textarea';

	public $settings = array(
		array(
			'name' 			=> 'from',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'count'			=> 'paragraphs',
			'description' 	=> 'Shortest length',
			'default'		=> 2
		),		
		array(
			'name' 			=> 'to',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'count'			=> 'paragraphs',
			'description' 	=> 'Longest length',
			'default' 		=> 6
		)
	);
}