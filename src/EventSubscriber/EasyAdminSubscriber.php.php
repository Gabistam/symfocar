<?php  
namespace App\EventSubscriber;

use App\Entity\Header;
use App\Entity\Product;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityUpdatedEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{ 
    private $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setIllustration'],
            BeforeEntityUpdatedEvent::class => ['updateIllustration']
        ];
    }

    public function uploadIllustration($event)
    {
        $entity = $event->getEntityInstance();
        
        $tmp_name = $_FILES['Product']['tmp_name']['illustration'];
        $filename = uniqid();
        $extension = pathinfo($_FILES['Product']['name']['illustration'], PATHINFO_EXTENSION);

        $project_dir = $this->appKernel->getProjectDir();

        move_uploaded_file($tmp_name, $project_dir . '/public/assets/images/uploads/' . $filename . '.' . $extension);

        $entity->setIllustration($filename . '.' . $extension);
    }

    public function updateIllustration(BeforeEntityUpdatedEvent $event)
    {
        if (!($event->getEntityInstance() instanceof Product) && !($event->getEntityInstance() instanceof Header)) {
            return;
        }

        $reflexion = new \ReflectionClass($event->getEntityInstance());
        $entityName = $reflexion->getShortName();

        if ($_FILES['Product']['tmp_name']['illustration'] !== '') {
            $this->uploadIllustration($event, $entityName);
        }
    }

    public function setIllustration(BeforeEntityPersistedEvent $event)
    {
        $reflexion = new \ReflectionClass($event->getEntityInstance());
        $entityName = $reflexion->getShortName();   
        

        if (!($event->getEntityInstance() instanceof Product) && !($event->getEntityInstance() instanceof Header)) {
            return;
        }

        $this->uploadIllustration($event, $entityName);
    }
}