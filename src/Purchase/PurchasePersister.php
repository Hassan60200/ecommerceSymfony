<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{

    protected $security;
    protected $cartService;
    protected $em;


    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->cartService = $cartService;
        $this->em =$em;
    }

    public function storePurchase(Purchase $purchase)
    {
        $user = $this->security->getUser();

//6)Nous allons lier la commande avec l'user connecté
        $purchase->setUser($user);

        $this->em->persist($purchase);

        //7) Nous lier la commande avec les produits dans le panier
        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->qty)
                ->setTotal($cartItem->getTotal());


            $this->em->persist($purchaseItem);
        }
    }
}