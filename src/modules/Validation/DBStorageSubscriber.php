<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 20:20
 */

namespace Objex\Validation;


use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Objex\Validation\Exceptions\ValidationException;
use Objex\Validation\Validators\DBStorageValidator;


class DBStorageSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove
        );
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        ( new DBStorageValidator($args, 'onPrePersist') )
            ->validate();
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        ( new DBStorageValidator($args, 'onPreUpdate') )
            ->validate();
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        ( new DBStorageValidator($args, 'onPreRemove') )
            ->validate();
    }
}