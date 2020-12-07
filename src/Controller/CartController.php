<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /** @var ProductRepository  */
    protected $productRepository;
    /** @var CartService  */
    protected $cartService;

    public function __construct(CartService $cartService, ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id": "\d+"})
     */
    public function add($id, Request $request): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas !");
        }

        $this->cartService->add($id);

        $this->addFlash('success', 'Le produit a bien été ajouter au panier !');

        if($request->query->get('returnToCart')){
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute('product_show', [
            'slug' => $product->getSlug(),
            'category_slug' => $product->getCategory()->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show()
    {
       $detailedCart = $this->cartService->getDetailedCartItems();

       $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'items' => $detailedCart,
            'total' => $total
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete($id){
        $product = $this->productRepository->find($id);

        if (!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne pourra pas être supprimer");
        }

        $this->cartService->remove($id);

        $this->addFlash("success", "Le produit a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");

    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement($id){
        $product = $this->productRepository->find($id);

        if (!$product){
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne pourra pas être décrementer");
        }
        $this->cartService->decrement($id);

        $this->addFlash("success", "Le produit a bien été décrementer du panier");


        return $this->redirectToRoute("cart_show");
    }
}
