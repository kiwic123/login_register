<?php
  if(!UserVeridator::isLogin(isset($_SESSION['memberID'])?$_SESSION['memberID']:'')){
    header('Location: '.Config::BASE_URL);
    exit;
  }

  $order = new Order();
  $cart = new Cart($_SESSION['cartQty'], $_SESSION['cartPrice'], $_SESSION['cartName']);

  if($order->orderTotal == 0){

    $order->setOrderComplete();
    $order->save();
    $_SESSION['order'] = base64_encode(serialize($order));
    header('Location: '.Config::BASE_URL."thankyou");
    exit;

  }else{

    $_SESSION['order'] = base64_encode(serialize($order));
    
    try {
      $obj = new ECPay_AllInOne();
      $obj->ServiceURL  = Config::ECPAY_API_URL;
      $obj->HashKey     = Config::ECPAY_HASH_KEY;
      $obj->HashIV      = Config::ECPAY_HASH_IV;
      $obj->MerchantID  = Config::ECPAY_MERCHANT_ID;
      $obj->Send['ReturnURL'] = Config::ECPAY_CALLBACK_URL;       //付款完成通知回傳的網址
      $obj->Send['MerchantTradeNo']   = $order->getOrderID();
      $obj->Send['MerchantTradeDate'] = date('Y/m/d H:i:s');      //交易時間
      $obj->Send['TotalAmount']       = (int)$order->orderTotal;  //交易金額
      $obj->Send['TradeDesc']         = $cart->toString();
      $obj->Send['NeedExtraPaidInfo'] = 'Y'; //額外的付款資訊(消費者信用卡末四碼)
      $obj->Send['OrderResultURL']    = Config::ECPAY_CALLBACK_URL; //付款完成導回平台的網址
      $obj->Send['ChoosePayment'] = ECPay_PaymentMethod::Credit;
      $obj->Send['EncryptType'] = 1;
  
      //訂單的商品資料
      $productIdArray = $cart->getAllProductID();
      foreach($productIdArray as $productID) {
        array_push($obj->Send['Items'], array(
          'Name'  => $cart->getProductNameInCart($productID),
          'Price'  => (int)$cart->getProductPriceInCart($productID),
          'Currency'  => "元",
          'Quantity'  => (int) $cart->getProductQtyInCart($productID),
          'URL'  => ""));
      }
  
      //產生訂單(auto submit至ECPay)
      $obj->CheckOut();
  
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    exit;

  }

  

  