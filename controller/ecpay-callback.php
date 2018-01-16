<?php

/**
 * 
 * print_r($_POST); 可得到 ECPAY 的回傳參數
 * Array
(
    [AlipayID] = 
    [AlipayTradeNo] = 
    [amount] = 100
    [ATMAccBank] = 
    [ATMAccNo] = 
    [auth_code] = 777777
    [card4no] = 2222
    [card6no] = 431195
    [eci] = 0
    [ExecTimes] = 
    [Frequency] = 
    [gwsr] = 10625000
    [MerchantID] = 2000132
    [MerchantTradeNo] = TWMO15161330399485
    [PayFrom] = 
    [PaymentDate] = 2018/01/17 04:05:23
    [PaymentNo] = 
    [PaymentType] = Credit_CreditCard
    [PaymentTypeChargeFee] = 1
    [PeriodAmount] = 
    [PeriodType] = 
    [process_date] = 2018/01/17 04:05:23
    [red_dan] = 0
    [red_de_amt] = 0
    [red_ok_amt] = 0
    [red_yet] = 0
    [RtnCode] = 1
    [RtnMsg] = Succeeded
    [SimulatePaid] = 0
    [staed] = 0
    [stage] = 0
    [stast] = 0
    [TenpayTradeNo] = 
    [TotalSuccessAmount] = 
    [TotalSuccessTimes] = 
    [TradeAmt] = 100
    [TradeDate] = 2018/01/17 04:04:00
    [TradeNo] = 1801170404001853
    [WebATMAccBank] = 
    [WebATMAccNo] = 
    [WebATMBankName] = 
    [CheckMacValue] = 5EB7AF8FEEFB1290072864A4A6615272A7BADE186E0E327314A0DC9C03A8B9A1
)
 */

 if(isset($_POST['RtnCode']) AND $_POST['RtnCode'] == 1)
 {
    $order = unserialize(base64_decode($_SESSION['order']));
    $order->setOrderComplete();
    $order->save();
    $_SESSION['order'] = base64_encode(serialize($order));
    header('Location: '.Config::BASE_URL."thankyou");
    exit;
 }else{
    header('Location: '.Config::BASE_URL."fail");
    exit;
 }

