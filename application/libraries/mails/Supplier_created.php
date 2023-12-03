<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Supplier_created extends App_mail_template
{
    protected $for = 'supplier';

    protected $supplier_email;

    protected $supplierid;

    public $slug = 'new-supplier-created';

    public $rel_type = 'supplier';

    public function __construct($supplier_email, $supplierid)
    {
        parent::__construct();
        $this->supplier_email       = $supplier_email;
        $this->supplierid           = $supplierid;
    }

    public function build()
    {
        $this->to($this->supplier_email)
        ->set_rel_id($this->supplierid)
        ->set_merge_fields('supplier_merge_fields', $this->supplierid);
    }
}
