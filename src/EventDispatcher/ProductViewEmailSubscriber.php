<?php

namespace App\EventDispatcher;


use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class ProductViewEmailSubscriber implements EventSubscriberInterface
{

    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {
        // TODO: Implement getSubscribedEvents() method.
        return [
            'product.view' => 'sendEmail'
        ];
    }

    public function sendEmail(ProductViewEvent $event)
    {
        /*$email = new TemplatedEmail();
        $email->from(new Address("contact@mail.com", "Information sur Symshop"))
            ->to("admin@mail.com")
            ->htmlTemplate('emails/product_view.html.twig')
            ->context([
                'product' => $event->getProduct()
            ])
            ->subject('visite du produit ' . $event->getProduct()->getId());

        $this->mailer->send($email);*/




        $this->logger->info("Email envoyé à l'admin pour le produit n°" . $event->getProduct()->getId());
    }
}