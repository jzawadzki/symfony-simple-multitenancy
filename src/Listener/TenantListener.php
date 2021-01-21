<?php declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use App\Tenant\TenantAware;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TenantListener
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof TenantAware) {

            /** @var User $user */
            $user = $this->security->getUser();
            if ($user) {
                $tenant = $user->getTenant();
                $entity->setTenant($tenant);
            }

        }
    }
}