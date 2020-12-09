<?php

namespace App\Stripe;


use App\Entity\Purchase;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService{

    protected $secretKey;
    protected $publicKey;

    public function __construct(string $secretKey, string $publicKey){

        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey(): string{
        return $this->publicKey;
    }

    public function getPaymentIntent(Purchase $purchase): PaymentIntent
    {
        Stripe::setApiKey($this->secretKey);

        return   PaymentIntent::create([
            'amount' => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}