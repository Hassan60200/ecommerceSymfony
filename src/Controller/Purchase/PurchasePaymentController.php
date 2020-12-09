<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController {


    /**
     *@Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @IsGranted("ROLE_USER", message="Vous devez être connecter pour pouvoir payer")
     */
    public function CardForm($id, PurchaseRepository $repository, StripeService $stripeService){

        $purchase = $repository->find($id);

        if(!$purchase || ($purchase && $purchase->getUser() !== $this->getUser()) || ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)){

            return $this->redirectToRoute('cart_show');
        }

        $intent = $stripeService->getPaymentIntent($purchase);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}