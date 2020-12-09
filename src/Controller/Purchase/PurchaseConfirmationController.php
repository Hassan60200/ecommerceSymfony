<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class PurchaseConfirmationController extends AbstractController
{
    protected $cartService;
    protected $em;
    protected $persister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour pouvoir passer une commande")
     */
    public function confirm(Request $request): RedirectResponse
    {
        //1) Nous voulons lire les données du formulaire
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);

        //2) Si le formulaire n'est pas soumis, message d'erreur + redirection
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'vous devez remplir le formulaire');
            return $this->redirectToRoute('cart_show');
        }

        //4) Si il n'y a pas de produits dans mon panier, redirection + message important
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', 'Vous ne pouvez pas confirmer une commande avec un panier vide');
            return $this->redirectToRoute('cart_show');
        }

        //5) Création d'une nouvelle commande
        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        //8) Nous allons enrengistrer la commande avec EntityManagerInterface
        $this->em->flush();

        $this->cartService->empty();

        $this->addFlash('success', 'la commande a bien été enrengistrer');
        return $this->redirectToRoute('purchase_index');

    }
}