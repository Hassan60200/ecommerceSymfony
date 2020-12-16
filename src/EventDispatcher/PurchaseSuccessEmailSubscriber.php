<?php

namespace App\EventDispatcher;

use App\Entity\User;
use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Security\Core\Security;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;
    protected $security;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            'purchase.success' => 'emailSuccess'
        ];
    }

    public function emailSuccess(PurchaseSuccessEvent $event)
    {
        //1) Je récupère l'adresse de l'utilisateur qui vient de passer un commande

        /** @var User */
        $currentUser = $this->security->getUser();

        $this->logger->info('est ce que ça marche ?' . $currentUser->getEmail());

        //2) je récupère la commande
        $purchase = $event->getPurchase();

        //3) Création de l'émail

        $email = new TemplatedEmail();

        $email->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->from('contact@mail.com')
            ->subject("Bravo, votre commande ({$purchase->getId()}) a bien été confirmé")
            ->htmlTemplate('emails/purchase_success.html.twig')
            ->context(
                [
                    'purchase' => $purchase,
                    'user' => $currentUser
                ]
            );

//4) Envoie de l'email
        $this->mailer->send($email);

        $this->logger->info("Confirmation du paiement du produit n°" . $purchase->getId());
    }


}