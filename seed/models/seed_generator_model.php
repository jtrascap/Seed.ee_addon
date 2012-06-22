<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Seed Generator Model class
 *
 * @package         seed_ee_addon
 * @version         0.1
 * @author          Joel Bradbury ~ <joel@squarebit.co.uk>
 * @link            http://squarebit.co.uk/seed
 * @copyright       Copyright (c) 2012, Joel 
 */
class Seed_generator_model extends Seed_model {

    
    private static $seed_text = '';
    private $table = '';
    private $order = 4;

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
            'seed_generator',
            'generator_id',
            array()
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


    public function initialize()
    {
        $this->EE->load->helper('file');

        $this->seed_text = read_file( SEED_TEXT_SRC );

    }

    public function generate_words( $length )
    {
        $length = $length * 5;

        $this->initialize();

        $table = $this->_generate_table( $this->order );

        $return = $this->_generate_text( $length, $table, $this->order );

        return $return;
    }


    public function generate_paragraphs( $length )
    {
        $length = $length * 100;
        
        $this->initialize();

        $table = $this->_generate_table( $this->order );

        $return = $this->_generate_text( $length, $table, $this->order );

        return $return;
    }


    public function _generate_table($look_forward )
    {

        $table = array();
        
        // now walk through the text and make the index table
        for ($i = 0; $i < strlen($this->seed_text); $i++) {
            $char = substr($this->seed_text, $i, $look_forward);
            if (!isset($table[$char])) $table[$char] = array();
        }              
        
        // walk the array again and count the numbers
        for ($i = 0; $i < (strlen($this->seed_text) - $look_forward); $i++) {
            $char_index = substr($this->seed_text, $i, $look_forward);
            $char_count = substr($this->seed_text, $i+$look_forward, $look_forward);
            
            if (isset($table[$char_index][$char_count])) {
                $table[$char_index][$char_count]++;
            } else {
                $table[$char_index][$char_count] = 1;
            }                
        } 

        return $table;
    }

    public function _generate_text($length, $table, $look_forward) 
    {
        // get first character
        $char = array_rand($table);
        $o = $char;

        for ($i = 0; $i < ($length / $look_forward); $i++) {
            $newchar = $this->_return_weighted_char($table[$char]);            
            
            if ($newchar) {
                $char = $newchar;
                $o .= $newchar;
            } else {       
                $char = array_rand($table);
            }
        }
        
        return $o;
    }

    public function _return_weighted_char($array) 
    {
        if (!$array) return false;
        
        $total = array_sum($array);
        $rand  = mt_rand(1, $total);
        foreach ($array as $item => $weight) {
            if ($rand <= $weight) return $item;
            $rand -= $weight;
        }
    }

}