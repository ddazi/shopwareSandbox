<?php declare(strict_types=1);

namespace SandBoxPlugin\StoreFront\Subscriber;

use SandBoxPlugin\Core\Content\SandBoxPlugin\SandBoxCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface
{
    private $systemConfigService;
    private $sandBoxRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $sandBoxRepository

    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->sandBoxRepository = $sandBoxRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void
    {
        if (!$this->systemConfigService->get('SandBoxPlugin.config.showInStoreFront')) {
            return;
        }
        $shops = $this->fetchShops($event->getContext());
        $event->getPagelet()->addExtension('SandBoxPlugin', $shops);
    }

    /**
     * @param Context $getContext
     * @return SandBoxCollection
     */
    private function fetchShops(Context $getContext): SandBoxCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addFilter(new EqualsFilter('active',1));
        $criteria->setLimit(5);

        /**
         * @var SandBoxCollection $sandBoxCollection
         */
        $sandBoxCollection = $this->sandBoxRepository->search($criteria,$getContext)->getEntities();

        return $sandBoxCollection;
    }
}
