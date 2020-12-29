<?php

namespace App\Listner;

use App\Entity\Annonce;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    /**
     * @var CacheManager
     */
    private $cacheManager;

    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;
    /**
     * Class constructor.
     */
    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    public function preRemove(LifecycleEventArgs $ArgsR)
    {
        $entity = $ArgsR->getEntity();
        if (!$entity instanceof Annonce) {
            return;
        }

        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile1'));
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile2'));
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile3'));
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile4'));
    }
    public function preUpdate(PreUpdateEventArgs $ArgsU)
    {
        $entity = $ArgsU->getEntity();
        $object = $ArgsU->getObject();

        if (!$ArgsU->getEntity() instanceof Annonce) {
            return;
        }
        $val = "/images/annonces/";

        if ($ArgsU->hasChangedField('fileName1')) {
            $this->cacheManager->remove($val . $ArgsU->getOldValue('fileName1'));
        }
        if ($ArgsU->hasChangedField('fileName2')) {
            $this->cacheManager->remove($val . $ArgsU->getOldValue('fileName2'));
        }
        if ($ArgsU->hasChangedField('fileName3')) {
            $this->cacheManager->remove($val . $ArgsU->getOldValue('fileName3'));
        }
        if ($ArgsU->hasChangedField('fileName4')) {
            $this->cacheManager->remove($val . $ArgsU->getOldValue('fileName4'));
        }
    }
}
