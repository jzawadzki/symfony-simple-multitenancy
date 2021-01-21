<?php declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use App\Filter\TenantFilter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Security\Core\Security;

class RequestListener
{

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var Security
     */
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        if (!$event->isMasterRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $token = $this->security->getToken();
        if (!$token) {
            return;
        }
        /** @var User $user */
        $user = $token->getUser();
        if(!$user||!$user instanceof User) {
            return;
        }
        /** @var TenantFilter $filter */
        $filter = $this->entityManager->getFilters()->getFilter('tenant_filter');

        $filter->setTenant($user->getTenant());
    }
}