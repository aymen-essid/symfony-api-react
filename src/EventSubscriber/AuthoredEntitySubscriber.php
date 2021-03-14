<?php


namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class AuthoredEntitySubscriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
   {
       return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
       ];
   }

   public function getAuthenticatedUser(ViewEvent $event)
   {
       $entity = $event->getControllerResult();
       $method = $event->getRequest()->getMethod();

       /** @var @var UserInterface $author */
       $author = $this->security->getUser();

       if(!$entity instanceof BlogPost || Request::METHOD_POST !== $method){
           return;
       }

       $entity->setAuthor($author);
   }

}