<?php
class Order {

    private $orderID;
    public $memberID;
    public $description;
    public $serviceFee = 0;
    public $shippingFee = 0;
    public $cartTotal = 0;
    public $orderTotal = 0;
    public $status = 0;

    function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->orderID = Config::COUNTRY_CODE.Config::SHOP_CODE.microtime(true)*10000;
        $this->memberID = $_SESSION['memberID'];
        $cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);
        $this->description = $cart->toString();
        $this->serviceFee = 0;
        $this->shippingFee = 0;
        $this->cartTotal = $cart->getCartTotalAmount();
        $this->orderTotal = $cart->getCartTotalAmount();
    }

    function setOrderComplete(){
        $this->status = 1;
    }

    function setOrderFail(){
        $this->status = -1;
    }

    function getOrderID(){
        return $this->orderID;
    }

    function save(){
        if(!empty($this->orderID)){
            if(OrderVeridator::isOrderIDExist($this->orderID)){
                $table = 'orders';
                $data_array = array(
                    'memberID' => $this->memberID,
                    'description' => $this->description,
                    'serviceFee' => $this->serviceFee,
                    'shippingFee' => $this->shippingFee,
                    'cartTotal' => $this->cartTotal,
                    'orderTotal' => $this->orderTotal,
                    'status' => $this->status
                );
                Database::get()->update($table, $data_array, "orderID", $this->orderID);
            }else{
                $table = 'orders';
                $data_array = array(
                    'orderID' => $this->orderID,
                    'memberID' => $this->memberID,
                    'description' => $this->description,
                    'serviceFee' => $this->serviceFee,
                    'shippingFee' => $this->shippingFee,
                    'cartTotal' => $this->cartTotal,
                    'orderTotal' => $this->orderTotal,
                    'status' => $this->status
                );
                Database::get()->insert($table, $data_array);
            }
        }
    }
}