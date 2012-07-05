<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seed Fieldtype Wygwam class
 *
 * @package         seed_ee_addon
 * @version         0.9.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */
class Seed_fieldtype_wygwam extends Seed_fieldtype
{
	public $title = 'wygwam';

	public $settings = array(
		array(
			'name' 			=> 'from',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'default'		=> 2
		),		
		array(
			'name' 			=> 'to',
			'required' 		=> TRUE,
			'type' 			=> 'int',
			'default' 		=> 6
		),


		array(
			'name' 			=> 'markup_a',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_strong',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_em',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_u',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h1',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h2',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h3',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h4',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h5',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_h6',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_blockquote',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_ul',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		),
		array(
			'name' 			=> 'markup_ol',
			'required' 		=> FALSE,
			'type' 			=> 'str',
			'default' 		=> 'n'
		)

	);

	private $field = array();
	private $paragraphs = array();

	public function generate( $field = array() )
	{
		$ret = '';

		// Generate some text within the bounds of the options
		if( $field['populate'] == 'sparse' )
		{
			// We don't want to always populate this. 
			if( rand( 1, 2 ) == 1 ) $ret = $this->_generate_filler( $field );
		}
		else $ret = $this->_generate_filler( $field );

		return $ret;
	}



	private function _generate_filler( $field = array() )
	{
		$this->field = $field;

		$ret = '';

		$length = rand( $this->field['from'], $this->field['to'] );
	
		$ret = $this->EE->seed_generator_model->generate_paragraphs( $length );

		$paragraphs = explode( "\n\n", $ret );

		// Do we have any additional markup to drop in?
		$additional = $this->_check_additional();

		if( $additional === FALSE )
		{
			// Just wrap the paragraphs with <p> tags
			$ret = '<p>' . implode( $paragraphs,"</p>\n <p>" ) . '</p>';

			return $ret;
		}
		else
		{
			// We have more work to do
			$ret = $this->_generate_additional( $paragraphs, $additional );			
		}

		return $ret;
	}


	private function _generate_additional( $paragraphs = array(), $additional = array() )
	{
		if( empty( $paragraphs ) ) return '';
		if( empty( $additional ) ) return '<p>' . implode( $paragraphs,"</p>\n <p>" ) . '</p>';

		$this->paragraphs = $paragraphs;

		$c = count( $this->paragraphs );


		// Handle the inline elements first
		// anchors, em, strong, u

		$this->_generate_anchor();


		// EM

		// STRONG

		// UNDERLINE



		// Now handle extra items. These require extra filler text to be generated

		return '<p>' . implode( $this->paragraphs,"</p>\n <p>" ) . '</p>';

	}

	private function _generate_anchor()
	{
		// ANCHORS
		// Generate 1 anchor per 3 paragraphs
		// anchors of lenth 1 - 4 words		
		$per_p = 3;
		$len_min = 1;
		$len_max = 4;

		for( $i = 1; $i <= floor( count( $this->paragraphs ) / $per_p ); $i++ )
		{
			// Pick a paragraph from these
			$j = rand( 1, $per_p ) + ( $per_p * ( $i - 1 ) ) - 1;
			$tmp = $this->paragraphs[ $j ];

			// How long will this anchor be?
			$anchor_length = rand( $len_min, $len_max );

			// Now pick somewhere in this paragraph
			$pos = rand( 0, strlen( $tmp ) );

			// Find the nearest preceding word break
			$s = strrpos( $tmp, ' ', -(strlen( $tmp ) - $pos) );

			if( $s === FALSE )
			{
				// None match, go from the start
				$s = 0;
			}
			else
			{
				// move past the space to the start of the word
				$s++;
			}

			// Move forward as many words as we need from anchor_length
			$looking = TRUE;
			$e = strlen( $tmp );
			$t = $s;
			$k = 0;

			while( $looking == TRUE )
			{
				$t++;

				if( $t > strlen( $tmp ) )
				{
					$looking = FALSE;
				}

				$t = strpos( $tmp, ' ', $t);

				if( $t === FALSE)
				{
					$looking = FALSE;
				}

				$k++;

				if( $k == $anchor_length ) 
				{
					$looking = FALSE;
					$e = $t;
				}
			}

			if( $e !== strlen( $tmp ) )
			{
				// Backspace one character to remove trailing whitespace
				$e--;
			}

			$pre = substr( $tmp, 0, $s );
			$string = substr( $tmp, $s, ( $e - $s + 1 ) );
			$post = substr( $tmp, $e+1 );

			// Now actually add the anchor
			// recombine them
			if( trim($string) != '' ) 
			{
				$tmp = $pre . '<a href="#">' . $string . '</a>' . $post;
			}

			$this->paragraphs[ $j ] = $tmp;
		}

	}



	private function _check_additional()
	{
		$has_additional = FALSE;
		$additional = array();

		foreach( $this->settings as $setting )
		{
			if( strpos( $setting['name'], 'markup' ) !== FALSE )
			{
				// Now check to see if the option was passed, and is set to 'y'
				if( isset( $this->field[ $setting['name'] ] ) AND $this->field[ $setting['name'] ] == 'y' )
				{
					$has_additional = TRUE;
					$additional[] = $setting['name'];
				}	
			}
		}

		if( ! $has_additional ) return FALSE;

		return $additional;
	}
}