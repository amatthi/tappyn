<?php defined("BASEPATH") or exit('No direct script access allowed');

class Vouchers extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$this->ion_auth->logged_in())
        {
            $this->responder->fail("Unauthorized Access")->code(401)->respond();
            exit();
        }
        $this->load->library('vouchers_library');
    }

    /**
     * Endpoint to validate a voucher on behalf of the user
     * @param  integer  $vid Voucher ID
     * @return void
     */
    public function is_valid($vid)
    {
        if($this->vouchers_library->is_valid($vid))
        {
            $price = 99.99;
            $voucher = $this->vouchers_library->get($vid);
            if($voucher->discount_type == 'amount')
            {
                $price = $price - $voucher->amount;
            } else {
                $price = $price - ($price * $voucher->amount);
            }

            $this->responder->data(array('is_valid' => true, 'price' => $price))->respond();
        } else {
            $this->responder->fail(($this->vouchers_library->errors() ? $this->vouchers_library->errors() : "Voucher invalid"))->code(500)->respond();
        }
    }
}
