<?php

namespace App\Service;

use Stripe\Stripe;
use Stripe\Product as StripeProduct;

class StripeService
{
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function createTicket(string $name, string $description): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        return StripeProduct::create([
            'name' => $name,
            'description' => $description,
            'type' => 'good',
            'attributes' => ['size', 'color'],
        ]);
    }

    public function updateTicket(string $ticketId, string $name, string $description): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        $stripeProduct = StripeProduct::retrieve($ticketId);
        $stripeProduct->name = $name;
        $stripeProduct->description = $description;
        $stripeProduct->save();
        return $stripeProduct;
    }


    public function deleteTicket(string $ticketId): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        $stripeProduct = StripeProduct::retrieve($ticketId);
        $stripeProduct->delete();
        return $stripeProduct;
    }
    
    public function createPrice(string $ticketId, int $price): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        return StripeProduct::create([
            'unit_amount' => $price,
            'currency' => 'usd',
            'product' => $ticketId,
        ]);
    }

    public function updatePrice(string $priceId, int $price): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        $stripeProduct = StripeProduct::retrieve($priceId);
        $stripeProduct->unit_amount = $price;
        $stripeProduct->save();
        return $stripeProduct;
    }

    public function deletePrice(string $priceId): StripeProduct
    {
        Stripe::setApiKey($this->secretKey);
        $stripeProduct = StripeProduct::retrieve($priceId);
        $stripeProduct->delete();
        return $stripeProduct;
    }
}
