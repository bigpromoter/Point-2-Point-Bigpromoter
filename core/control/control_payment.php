<?php
/* Itens to be used with PayPal Gateway */
    use PayPal\Api\Amount;
    use PayPal\Api\Details;
    use PayPal\Api\Item;
    use PayPal\Api\ItemList;
    use PayPal\Api\CreditCard;        
    use PayPal\Api\Payer;
    use PayPal\Api\Payment;
    use PayPal\Api\FundingInstrument;
    use PayPal\Api\Transaction;

class P2P_Payment {
    private $option;
    private $control_form;
    private $card_type;
	function __construct() {
        $this->option = get_option('p2p_bp');
        include_once(P2P_DIR_CONTROL.'form.php');
        $this->control_form = new P2P_Form();
        $this->card_type = array('visa','mastercard','amex','discover');
	}
    
   //BrainTree Payment
    function paymentCallBrainTree() {
        $output = '';
        $output .= '<script src="https://js.braintreegateway.com/v1/braintree.js"></script>'.PHP_EOL;
        $output .= '<script>'.PHP_EOL;
        $output .= 'var braintree = Braintree.create("'.$this->option['payment']['braintree_config_code'].'");'.PHP_EOL;
        $output .= 'braintree.onSubmitEncryptForm("checkout");'.PHP_EOL;
        $output .= '</script>'.PHP_EOL;
        return $output;
    }
    
    function paymentBrainTree($price, $info) {
        require_once (P2P_DIR_PAYMENT_BRAINTREE.'lib/Braintree.php');
    
        Braintree_Configuration::environment($this->option['payment']['braintree_enviroment']);
        Braintree_Configuration::merchantId($this->option['payment']['braintree_merchantId']);
        Braintree_Configuration::publicKey($this->option['payment']['braintree_publicKey']);
        Braintree_Configuration::privateKey($this->option['payment']['braintree_privateKey']);
        
        $desc = ($info['r'])?' (Round Trip)':'';
         
        $paymentInfo = array('amount' => $price,
                            'type' => Braintree_Transaction::SALE,
                            'customer' => array(
                                                    'firstName' => $info['first_name'],
                                                    'lastName' => $info['last_name'],
                                                    'phone' => $info['phone'],
                                                    'email' => $info['email']
                                                ),
                            'creditCard' => array(
                                                    'number' => str_replace(' ','',$info['card_num']),
                                                    'cvv' => $info['card_cvv'],
                                                    'expirationMonth' => $info['card_month'],
                                                    'expirationYear' => $info['card_year']
                                                )
                        );
         
        $output[3] = $paymentInfo;
        $result = Braintree_Transaction::sale($paymentInfo);
        $output[0] = false;
        $output[1] = ''; //Message
        $output[2] = 0; //Value
        $output[3] = ''; //Transaction Id
        $output[4] = 'BrainTree'; //Company
        if ($result->success) {
            $output[0] = true;
            $output[1] .= "<div class= p2p_bp_alert p2p_bp_success'>";
            $output[1] .= __("We got your Payment on ",P2P_TRANSLATE)."BrainTree! [".__("Transaction code", P2P_TRANSLATE).": ". $result->transaction->id."]<BR>";
            $output[1] .= __("Amount Paid",P2P_TRANSLATE).": ".$this->option['basic']['select_currency']."{$price}".$desc;
            $output[1] .= "</div>";
            $output[2] = $value;
            $output[3] = $result->transaction->id;
        } else if ($result->transaction) {
            $output[1] .= "<div class='p2p_bp_alert p2p_bp_error'>";
            $output[1] .= "<BR>".__("Error processing transaction", P2P_TRANSLATE).":";
            $output[1] .= "<BR>  ".__("message",P2P_TRANSLATE).": " . $result->message;
            $output[1] .= "<BR>  ".__("code",P2P_TRANSLATE).": " . $result->transaction->processorResponseCode;
            $output[1] .= "<BR>  ".__("text",P2P_TRANSLATE).": " . $result->transaction->processorResponseText;
            $output[1] .= "</div>";
        } else {
            $output[1] .= "<div class='p2p_bp_alert p2p_bp_error'>";
            $output[1] .= __("Validation errors on your Payment", P2P_TRANSLATE).": <BR>";            
            foreach (($result->errors->deepAll()) as $error) {
                $output[1] .= "- " . $error->message . "<br/>";
            }                    
            $output[1] .= "<BR>";
            $output[1] .= __("Your reservation FAILED! Please Contact Us!", P2P_TRANSLATE);
            $output[1] .= "</div>";
        }
        
        return $output;
    }
    //End BrainTree
    
    //Paypal Payment
    function paymentPayPal($price, $info) {
        require P2P_DIR_PAYMENT_PAYPAL.'bootstrap.php';
        
        // ### CreditCard
        // A resource representing a credit card that can be
        // used to fund a payment.
        $card = new CreditCard();
        $card->setType($info['cardtype'])
            ->setNumber($info['card_num'])
            ->setExpireMonth($info['card_month'])
            ->setExpireYear($info['card_year'])
            ->setCvv2($info['card_cvv'])
            ->setFirstName($info['first_name'])
            ->setLastName($info['last_name']);
        
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard
        // field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);
        
        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));
        
        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        
        //If Round Trip charge double
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($price);
        
        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it.
        ($info['r'])?$desc=' (Round Trip)':$desc='';
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setDescription("Transport - From: {$info['p_address']} To: {$info['d_address']}".$desc);
        
        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));
        
        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        $output[0] = false; //Result of Payment
        $output[1] = ''; //Message
        $output[2] = 0; //Value
        $output[3] = ''; //Transaction ID
        $output[4] = 'PayPal';
        try {
            $result = $payment->create($apiContext);
            $output[0] = true;
            $output[1] .= "<div class='p2p_bp_alert p2p_bp_success'>";
            $output[1] .= __("We got your Payment on ",P2P_TRANSLATE)."PayPal! [".__("Transaction code", P2P_TRANSLATE).": ". $result->id."]<BR>";
            $output[1] .= __("Amount Paid",P2P_TRANSLATE).": ".$this->option['basic']['select_currency']."{$price}".$desc;
            $output[1] .= "</div>";
            $output[2] = $price;
            $output[3] = $result->id;
        } catch (PayPal\Exception\PPConnectionException $ex) {
            $error = explode('"',$ex->getData());
            //ResultPrinter::printError('Create Payment Using Credit Card. If 500 Exception, try creating a new Credit Card using <a href="https://ppmts.custhelp.com/app/answers/detail/a_id/750">Step 4, on this link</a>, and using it.', 'Payment', null, $request, $ex);
            $output[1] .= "<div class='p2p_bp_alert p2p_bp_error'>";
            $output[1] .= __("Validation errors on your Payment", P2P_TRANSLATE).": <BR>";
            $output[1] .= __("Message",P2P_TRANSLATE).": ".$ex->getMessage()."<BR>";
            $output[1] .= "<BR>";
            $output[1] .= __("Your reservation FAILED! Please Contact Us!", P2P_TRANSLATE);
            $output[1] .= "</div>";
        }
        return $output;
    }
    
    function selectCartType($value) {
        
        $cardType = $this->card_type;
        $output = $this->control_form->startDiv();
        $output .= '<div class="w100p">';
        $output .= '<select class="p2p_bp_reservation_select" id="cardtype" name="cardtype">';
        $output .= '<option value="0" selected="selected">['.__('Choose your Card',P2P_TRANSLATE).']</option>';
        foreach ($cardType as $card) {
            ($value == $card)?$selected='selected':$selected='';
            $output .= '<option value="'.$card.'" '.$selected.'>'.ucfirst($card).'</option>';            
        }
        $output .= '</select>';
        $output .= '</div> ';
        
        $output .= $this->control_form->endDiv();

        return $output;
    }  
}
?>