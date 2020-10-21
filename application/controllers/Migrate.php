<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * @class           :   Migrate
 * @author          :   Adeel Ahmad Rao
 * @description     :   Migrates database to any revision
 * @created at      :   18-08-2016
 * @last modified   :   n/a
 * @modified by     :   n/a
 */

class Migrate extends CI_Controller {

    /*
    * @function        :   index
    * @author          :   Adeel Ahmad Rao
    * @description     :   Migrates database to any revision
    * @created at      :   18-08-2016
    * @last modified   :   n/a
    * @modified by     :   n/a
    */
    public function index() {
        #load migration library
        $this->load->library('migration');
        #move to current migration or display error
        if ($this->migration->current() === FALSE) {
            show_error($this->migration->error_string());
        }else{
            echo "....Migrations Ran Successfully....";
        }
    }

}
