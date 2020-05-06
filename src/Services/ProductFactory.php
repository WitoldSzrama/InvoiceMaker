<?php


namespace App\Services;


use App\Entity\Product;
use App\Entity\User;

class ProductFactory
{
    const CURRENCY = 'PLN';
    const VAT = [5 ,7 ,12 ,15 ,17, 23];
    public function createProduct(User $user)
    {
        $product = new Product();
        $product->setCurrency(self::CURRENCY);
        $product->setUser($user);

        return $product;
    }

    public function getVatChoices()
    {
        $vatArray = [];
        foreach (self::VAT as $vat) {
            $vatArray[] = $vat . ' %';
        }
        return array_combine($vatArray, self::VAT);
    }

}