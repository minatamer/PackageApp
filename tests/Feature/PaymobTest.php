<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Tests\TestCase;
use OProjects\Paymob\Controllers\PaymobController;
use Illuminate\Support\Facades\Session;

class PaymobTest extends TestCase
{
    public function test_authentication_request_returns_a_token()
    {
        $response = $this->post('/authenticationRequest');
        $token = $response->getContent();
        $this->assertNotNull($token);
        $this->assertNotEmpty($token);
        //echo($token);
    }

    public function test_order_request_returns_an_id()
    {
        $request = [
            'delivery_needed'=> 'false',
            'amount_cents'=> '100',
            'shipping_details' => null,
            'items' => []
        ];
        $response = $this->post('/orderRequest' , $request);
        $order_id = $response->getContent();
        $this->assertNotNull($order_id);
        $this->assertNotEmpty($order_id);
        //echo($order_id);
    }

    public function test_payment_request_redirects_to_payment_screen()
    {
        $items = [];
        $billing_data = [
            "apartment" => "803", 
            "email"=> "claudette09@exa.com", 
            "floor"=>"42", 
            "first_name"=> "Clifford", 
            "street"=> "Ethan Land", 
            "building"=> "8028", 
            "phone_number"=> "+86(8)9135210487", 
            "shipping_method"=> "PKG", 
            "postal_code"=> "01898", 
            "city" =>"Jaskolskiburgh", 
            "country"=> "CR", 
            "last_name"=> "Nicolas", 
            "state"=> "Utah"
        ];
        $request = [
            'delivery_needed'=> 'false',
            'amount_cents'=> '100',
            'billing_data'=> $billing_data,
            'items'=> $items
        ];
        $response = $this->post('/paymentRequest' , $request);
        $response->assertRedirect();
        echo($response->getContent());
    }



}