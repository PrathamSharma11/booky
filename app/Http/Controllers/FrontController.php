<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Cart;
use Auth;
use DB;
use App\Models\Order;
use App\Models\Order_item;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function index(){
        $slider=Slider::latest()->get();
        $category=Category::latest()->get();
        $product=Product::latest()->get();
        return view('front.index',compact('slider','category','product'));
    }
    public function product_detail($id){
        $product2=Product::latest()->get();
        $product=Product::find($id);
        return view('front.product_detail',compact('product','product2'));
    }












    public function cart(Request $request){
        //echo'<pre>';
        //print_r($data->all());
        $session_id = Session::getId();
        //echo $session_id;
        //die;
        $data  =  new Cart();
        if(Auth::check()){
            $user_email=Auth::user()->email;
            $data->user_email=$user_email;
        }
        $data -> product_id=$request->product_id;
        $data -> product_name=$request->product_name;
        $data -> product_price=$request->product_price;
        $data -> product_quantity=$request->product_quantity;
        $data -> product_image=$request->product_image;
        $data -> session_id=$session_id;
        $data->save();
        if($data){
            return redirect('add_to_cart');
        }
    }
    public function add_to_cart(){
       if(Auth::check()){
        $user_email=Auth::user()->email;
        $cart = Cart::where('user_email',$user_email)->get();
       }else{
        $session_id =Session::getId();
        $cart = Cart::where('session_id',$session_id)->get();

       }
       $product=Product::latest()->get();
        return view('front.add_to_cart',compact('product','cart'));
    }
    public function delete($id){
        $cart = Cart::find($id)->delete();
        return redirect('add_to_cart');

    }

    public function checkout(){
        if(Auth::check()){
            $user_email=Auth::user()->email;
            // $session_id = Session::getId();
            $cart= Cart::where('user_email',$user_email)->get();
            $product=Product::latest()->get();
            return view('front.checkout',compact('cart','product'));
        }
        else{
            return redirect('/login');
        }



    }
    public function place_order(Request $a){
        // echo '<pre>';
        // print_r($data->all());
        $data = new Order();
        $data->user_id=$a->user_id;
        $data->user_email=$a->user_email;
        $data->name=$a->name;
        $data->address=$a->address;
        $data->city=$a->city;
        $data->state=$a->state;
        $data->phone_num=$a->phone_num;
        $data->pin_code=$a->pin_code;
        $data->payment_method=$a->payment_method;
        $data->order_id=Str::random(10);
        $data->grand_total=$a->grand_total;
        $data->save();
        $order_id = DB::getPdo()->lastInsertID();
        $order_item = Cart::where('user_email',$data->user_email)->get();
        foreach($order_item as $item){
            $o_item = new Order_item;
            $o_item->order_id=$order_id;
            $o_item->user_id=$a->user_id;
            $o_item->user_email=$item->user_email;
            $o_item->product_name=$item->product_name;
            $o_item->product_price=$item->product_price;
            $o_item->product_quantity=$item->product_quantity;
            $o_item->product_image=$item->product_image;
            $o_item->save();

        }
        if($data['payment_method']=='cod'){
            return redirect('/');
        }
        elseif($data['payment_method']=='paytm'){
            $amount = $data->grand_total;
            $order_id = $data->order_id;
            // echo $amount;
            // echo $order_id;

            $data_for_request = $this->handlePaytmRequest( $order_id, $amount );
            // print_r($data_for_request);
            // die;


            $paytm_txn_url = 'https://securegw-stage.paytm.in/theia/processTransaction';
            $paramList = $data_for_request['paramList'];
            $checkSum = $data_for_request['checkSum'];

            return view( 'paytm-merchant-form', compact( 'paytm_txn_url', 'paramList', 'checkSum' ) );

        }




    }
    //paytm code

    public function handlePaytmRequest( $order_id, $amount ) {
        // Load all functions of encdec_paytm.php and config-paytm.php
        $this->getAllEncdecFunc();
        $this->getConfigPaytmSettings();

        $checkSum = "";
        $paramList = array();

        // Create an array having all required parameters for creating checksum.
        $paramList["MID"] = 'iApWjR91794694034184';
        $paramList["ORDER_ID"] = $order_id;
        $paramList["CUST_ID"] = $order_id;
        $paramList["INDUSTRY_TYPE_ID"] = 'Retail';
        $paramList["CHANNEL_ID"] = 'WEB';
        $paramList["TXN_AMOUNT"] = $amount;
        $paramList["WEBSITE"] = 'WEBSTAGING';
        $paramList["CALLBACK_URL"] = url( '/paytm-callback' );
        $paytm_merchant_key = 'wWHFDL3VdI#wErQx';

    //Here checksum string will return by getChecksumFromArray() function.
    $checkSum = getChecksumFromArray( $paramList, $paytm_merchant_key );

    return array(
        'checkSum' => $checkSum,
        'paramList' => $paramList
    );
}

public function paytmCallback( Request $request ) {
    // return $request;
    $order_id = $request['ORDERID'];

    if ( 'TXN_SUCCESS' === $request['STATUS'] )
     {
        $transaction_id = $request['TXNID'];
        $order = Order::where( 'order_id', $order_id )->first();
        $order->payment_status = 'complete';
        $order->transaction_id = $transaction_id;
        $order->save();
        $user_email = Auth::user()->email;
        Cart::where('user_email',$user_email)->delete();
        $category = Category::all();
        $data = Order::where('user_email',$user_email)->first();
        return view('front.thanks',compact('order','category','data'));

      } else if( 'TXN_FAILURE' === $request['STATUS'] ){
        return view( 'payment-failed' );
    }
}

public function getAllEncdecFunc(){


    function encrypt_e($input, $ky) {
        $key   = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_encrypt ( $input , "AES-128-CBC" , $key, 0, $iv );
        return $data;
    }
    function decrypt_e($crypt, $ky) {
        $key   = html_entity_decode($ky);
        $iv = "@@@@&&&&####$$$$";
        $data = openssl_decrypt ( $crypt , "AES-128-CBC" , $key, 0, $iv );
        return $data;
    }
    function generateSalt_e($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
        $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
        $data .= "0FGH45OP89";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }
    function checkString_e($value) {
        if ($value == 'null')
            $value = '';
        return $value;
    }
    function getChecksumFromArray($arrayList, $key, $sort=1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = getArray2Str($arrayList);
        $salt = generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = encrypt_e($hashString, $key);
        return $checksum;
    }
    function getChecksumFromString($str, $key) {

        $salt = generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = encrypt_e($hashString, $key);
        return $checksum;
    }
    function verifychecksum_e($arrayList, $key, $checksumvalue) {
        $arrayList = removeCheckSumParam($arrayList);
        ksort($arrayList);
        $str = getArray2StrForVerify($arrayList);
        $paytm_hash = decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;
        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }
    function verifychecksum_eFromStr($str, $key, $checksumvalue) {
        $paytm_hash = decrypt_e($checksumvalue, $key);
        $salt = substr($paytm_hash, -4);
        $finalString = $str . "|" . $salt;
        $website_hash = hash("sha256", $finalString);
        $website_hash .= $salt;
        $validFlag = "FALSE";
        if ($website_hash == $paytm_hash) {
            $validFlag = "TRUE";
        } else {
            $validFlag = "FALSE";
        }
        return $validFlag;
    }
    function getArray2Str($arrayList) {
        $findme   = 'REFUND';
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pos = strpos($value, $findme);
            $pospipe = strpos($value, $findmepipe);
            if ($pos !== false || $pospipe !== false)
            {
                continue;
            }

            if ($flag) {
                $paramStr .= checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . checkString_e($value);
            }
        }
        return $paramStr;
    }
    function getArray2StrForVerify($arrayList) {
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            if ($flag) {
                $paramStr .= checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . checkString_e($value);
            }
        }
        return $paramStr;
    }
    function redirect2PG($paramList, $key) {
        $hashString = getchecksumFromArray($paramList);
        $checksum = encrypt_e($hashString, $key);
    }
    function removeCheckSumParam($arrayList) {
        if (isset($arrayList["CHECKSUMHASH"])) {
            unset($arrayList["CHECKSUMHASH"]);
        }
        return $arrayList;
    }

    function getTxnStatus($requestParamList) {
        return callAPI(PAYTM_STATUS_QUERY_URL, $requestParamList);
    }
    function getTxnStatusNew($requestParamList) {
        return callNewAPI(PAYTM_STATUS_QUERY_NEW_URL, $requestParamList);
    }
    function initiateTxnRefund($requestParamList) {
        $CHECKSUM = getRefundChecksumFromArray($requestParamList,PAYTM_MERCHANT_KEY,0);
        $requestParamList["CHECKSUM"] = $CHECKSUM;
        return callAPI(PAYTM_REFUND_URL, $requestParamList);
    }
    function callAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData =json_encode($requestParamList);
        $postData = 'JsonData='.urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse,true);
        return $responseParamList;
    }
    function callNewAPI($apiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData =json_encode($requestParamList);
        $postData = 'JsonData='.urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData))
        );
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse,true);
        return $responseParamList;
    }
    function getRefundChecksumFromArray($arrayList, $key, $sort=1) {
        if ($sort != 0) {
            ksort($arrayList);
        }
        $str = getRefundArray2Str($arrayList);
        $salt = generateSalt_e(4);
        $finalString = $str . "|" . $salt;
        $hash = hash("sha256", $finalString);
        $hashString = $hash . $salt;
        $checksum = encrypt_e($hashString, $key);
        return $checksum;
    }
    function getRefundArray2Str($arrayList) {
        $findmepipe = '|';
        $paramStr = "";
        $flag = 1;
        foreach ($arrayList as $key => $value) {
            $pospipe = strpos($value, $findmepipe);
            if ($pospipe !== false)
            {
                continue;
            }

            if ($flag) {
                $paramStr .= checkString_e($value);
                $flag = 0;
            } else {
                $paramStr .= "|" . checkString_e($value);
            }
        }
        return $paramStr;
    }
    function callRefundAPI($refundApiURL, $requestParamList) {
        $jsonResponse = "";
        $responseParamList = array();
        $JsonData =json_encode($requestParamList);
        $postData = 'JsonData='.urlencode($JsonData);
        $ch = curl_init($apiURL);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $refundApiURL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $jsonResponse = curl_exec($ch);
        $responseParamList = json_decode($jsonResponse,true);
        return $responseParamList;
    }
  }



        function getConfigPaytmSettings(){

            define('PAYTM_ENVIRONMENT', 'PROD'); // PROD
            define('PAYTM_MERCHANT_KEY', 'EBPwh5dj_XmW1L7%'); //Change this constant's value with Merchant key received from Paytm.
            define('PAYTM_MERCHANT_MID', 'EbtGYn83534967686723'); //Change this constant's value with MID (Merchant ID) received from Paytm.
            define('PAYTM_MERCHANT_WEBSITE', 'DEFAULT'); //Change this constant's value with Website name received from Paytm.
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw-stage.paytm.in/order/status';
            $PAYTM_TXN_URL='https://securegw-stage.paytm.in/order/process';
            if (PAYTM_ENVIRONMENT == 'PROD') {
            $PAYTM_STATUS_QUERY_NEW_URL='https://securegw.paytm.in/merchant-status/getTxnStatus';
            $PAYTM_TXN_URL='https://securegw.paytm.in/theia/processTransaction';
        }
            define('PAYTM_REFUND_URL', '');
            define('PAYTM_STATUS_QUERY_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_STATUS_QUERY_NEW_URL', $PAYTM_STATUS_QUERY_NEW_URL);
            define('PAYTM_TXN_URL', $PAYTM_TXN_URL);
        }






























        public function thanks(){
        //echo "Order Successfully Placed";
        $user_email = Auth::user()->email;
        Cart::where('user_email',$user_email)->delete();
        $order = Order::where('user_email',$user_email)->first();
        $items = Order_item::where('order_id',$order->id)->get();

        //echo "Order Successfully Placed";
         return view('front.thanks',compact('order','items'));

    }

    public function contact(){
        return view('front.contact');
    }
    public function privacy(){
        return view('front.privacy');
    }
    public function terms(){
        return view('front.terms');
    }
    public function customer(){
        return view('front.customer');
    }
    public function about(){
        return view('front.about');
    }

}









