<?php
namespace RestBundle\EventListener;

use RestBundle\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * Class UserListener
 * @package AppBundle\EventListener
 */
class UserListener
{
    /** @var UserPasswordEncoder $passwordEncoder */
    private $passwordEncoder;

    /**
     * UserListener constructor.
     * @param UserPasswordEncoder $passwordEncoder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User) {
            if ($args->hasChangedField('password')) {
                $entity->setPassword(
                    $this->passwordEncoder->encodePassword($entity, $entity->getPassword())
                );
            }
        }
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof User && $entity->getPassword() !== null) {
            $entity->setPassword(
                $this->passwordEncoder->encodePassword($entity, $entity->getPassword())
            );
        }
    }
}