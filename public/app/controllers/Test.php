<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends Frontend_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function pdf() {
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $mpdf->Output();
    }

}
