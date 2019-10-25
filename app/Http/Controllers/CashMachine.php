<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Atm;

class CashMachine extends Controller
{

	private static $bills = array();

	function __construct() {
		// init

    }

    public static function index() {
    	
    	return view('atm', ['index' => true]);
    }

    public static function show() {
    	$bills = DB::table('bills')->get();
    	return view('show', ['bills' => $bills]);
    }

    public static function add() {
    	$bill = $_GET['bill'];
    	

    	DB::table('bills')->insert(['value' => $bill]);
    	$bills = DB::table('bills')->get();
    	
    	return view('show', ['bills' => $bills]);
    }

     public static function remove() {
    	$bill = $_GET['id'];

    	DB::table('bills')->where(['id' => $bill])->delete();
    	$bills = DB::table('bills')->get();
    	
    	return view('show', ['bills' => $bills]);
    }

    public static function getBills() {
    	$amount = $_GET['amount'];
    	return view('wd', ['result' => Atm::getBills($amount)]);
    }
}
