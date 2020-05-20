<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\Users;

class ProductFactory
{
    const CURRENCY = 'PLN';
    const VAT = [5, 7, 12, 15, 17, 23];

    public function createProduct(Users $user)
    {
        $product = new Product();
        $product->setCurrency(self::CURRENCY);
        $product->setUser($user);

        return $product;
    }

    public function getVatChoices(Users $user)
    {
        $baseVat = explode(', ', $user->getBaseVat());
        $vatArray = [];
        foreach ($baseVat as $vat) {
            $vatArray[] = $vat.' %';
        }

        return array_combine($vatArray, $baseVat);
    }
}
