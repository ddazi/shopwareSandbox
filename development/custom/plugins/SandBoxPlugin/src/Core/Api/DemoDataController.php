<?php declare(strict_types=1);

namespace SandBoxPlugin\Core\Api;

use Faker\Factory;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Country\Exception\CountryNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DemoDataController
 * @RouteScope(scopes={"api"})
 */
class DemoDataController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $sandBoxRepository;

    public function __construct(EntityRepositoryInterface $countryRepository, EntityRepositoryInterface $sandBoxRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->sandBoxRepository = $sandBoxRepository;
    }

    /**
     * @Route("/api/v{version}/_action/sand_box_plugin/generate", name="api.custom.sand_box_plugin.generate", methods={"POST"})
     * @param Context $context
     * @return Response
     */
    public function generate(Context $context): Response
    {
        $faker = Factory::create();
        $country = $this->getActiveCountry($context);

        $data = [];

        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'id' => Uuid::randomHex(),
                'active' => true,
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'postCode' => $faker->postcode,
                'city' => $faker->city,
                'countryId' => $country->getId(),
            ];
        }
        $this->sandBoxRepository->create($data, $context);

        return new Response('',Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Context $context
     * @return CountryEntity
     */
    private function getActiveCountry(Context $context): CountryEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(1);

        $country = $this->countryRepository->search($criteria,$context)->getEntities()->first();

        if ($country === null) {
            throw new CountryNotFoundException('');
        }
        return $country;
    }
}
