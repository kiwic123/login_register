<?php
/**
 * 耦合使用 Database 物件進行資料庫驗證 Order ID 是否已存在於資料庫
 */
class OrderVeridator {

    /**
     * 驗證帳號是否已存在於資料庫中
     */
    public static function isOrderIDExist($orderID){
        $result = Database::get()->execute('SELECT OrderID FROM order WHERE orderID = :orderID', array(':orderID' => $orderID));
        if(isset($result[0]['orderID']) and !empty($result[0]['orderID'])){
            return true;
        }
		return false;
    }
}