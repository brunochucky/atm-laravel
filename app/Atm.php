<?php
namespace App;
use Illuminate\Support\Facades\DB;
class WithdrawException extends \Exception {
    
}
class Atm {
    private static $bills = array();
    private static $money_left;
    private static $cash = array();
    function __construct() {
        
    }
    
    public static function addBill($bill) {
        array_push(self::$bills, $bill);
    }

    public static function getBill() {
        return self::$bills;
    }

    public static function getBills($withdrawAmount) {
        $qry_bills = DB::table('bills')->get();  
        foreach($qry_bills as $row) {
            array_push(self::$bills, $row->value);
        }
        self::$money_left = $withdrawAmount;
        while (self::$money_left > 0) {
            if (self::$money_left < min(self::$bills)) {
                throw new WithdrawException('This amount cannot be paid.');
            }
            $bill = self::configureBills();
            self::$cash[] = $bill;
            self::$money_left -= $bill;
        }
        return array_count_values(self::$cash);
    }


    private static function configureBills() {
        foreach (self::$bills as $bill) {
            $division = self::$money_left / $bill;
            $rest = self::$money_left % $bill;
            $checkDivision = ($division >= 1);
            $checkRest = ( $rest > (min(self::$bills)+1) || ($rest === min(self::$bills)) || ($rest === 0) );
            if ( $checkDivision && $checkRest ) {
                return $bill;
            }
        } 
        return min(self::$bills);
    }
}
?>