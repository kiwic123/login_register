<?php
class Cart {

    private $cartQty;
    private $cartPrice;
    private $cartName;

    function __construct($cartQty, $cartPrice, $cartName) {
        if(is_array($cartQty) AND is_array($cartPrice) AND is_array($cartName)){
            $this->cartQty   = $cartQty;
            $this->cartPrice = $cartPrice;
            $this->cartName  = $cartName;
        }else{
            $this->cartQty   = array();
            $this->cartPrice = array();
            $this->cartName  = array();
        }
    }

    function getCartQty(){
        return $this->cartQty;
    }

    function getCartPrice(){
        return $this->cartPrice;
    }

    function getCartName(){
        return $this->cartName;
    }

    function toString(){
        $temp = '';
        foreach($this->getAllProductID() as $productID){
            $temp .= $this->getProductNameInCart($productID)." x ".$this->getProductQtyInCart($productID). " $".$this->getProductPriceInCart($productID).", ";
        }
        return substr_replace($temp, "", -2);
    }

    function getTotalQty(){
        $count = 0;
        foreach($this->cartQty as $qty){
            $count += $qty;
        }
        return $count;
    }

    function getCartTotalAmount(){
        $amount = 0;
        foreach($this->cartQty as $productID => $qty){
            $amount += $this->cartPrice[$productID] * $qty;
        }
        return $amount;
    }

    function addToCart($productID, $name, $price, $qty)
    {
        if(is_numeric($productID) AND is_numeric($qty) AND !empty($productID) AND !empty($name) AND !empty($qty)){
            if(array_key_exists($productID, $this->cartQty)) {
                $this->cartQty[$productID] += $qty;
            }else{
                $this->cartQty[$productID] = $qty;
                $this->cartName[$productID] = $name;
                $this->cartPrice[$productID] = $price;
            } 
            return true;
        }
        return false;
    }

    function addToCartIfNotExist($productID, $name, $price, $qty)
    {
        if(is_numeric($productID) AND is_numeric($qty) AND !empty($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                return false;
            }else{
                $this->cartQty[$productID] = $qty;
                $this->cartName[$productID] = $name;
                $this->cartPrice[$productID] = $price;
                return true;
            }
        }
        return false;
    }

    function removeOneFromCart($productID)
    {
        if(is_numeric($productID) AND !empty($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                if($this->cartQty[$productID] >= 1){
                    $this->cartQty[$productID]--;
                }
                if($this->cartQty[$productID] <= 0){
                    unset($this->cartQty[$productID]);
                    unset($this->cartName[$productID]);
                    unset($this->cartPrice[$productID]);
                }
                return true;
            }
        }
        return false;
    }

    function removeProductFromCart($productID){
        if(is_numeric($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                unset($this->cartQty[$productID]);
                unset($this->cartName[$productID]);
                unset($this->cartPrice[$productID]);
                return true;
            }
        }
        return false;
    }

    function getProductPriceInCart($productID){
        if(is_numeric($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                return $this->cartPrice[$productID];
            }
        }
    }

    function getProductNameInCart($productID){
        if(is_numeric($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                return $this->cartName[$productID];
            }
        }
    }

    function getProductQtyInCart($productID){
        if(is_numeric($productID)){
            if(array_key_exists($productID, $this->cartQty)) {
                return $this->cartQty[$productID];
            }
        }
    }

    function getAllProductID(){
        return array_keys($this->cartQty);
    }
}