<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class WooLib
{
	function __construct()
	{	
		require_once( 'woocommerce/woocommerce-api.php' );
    }
}