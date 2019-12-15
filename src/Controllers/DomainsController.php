<?php

namespace App\Controllers;

use App\Controllers\Dtos\DomainDto;
use App\Controllers\Params\GetDomainPricesParams;
use App\Utils\Domains;
use App\Utils\ObjectArrays;
use Doctrine\ORM\EntityManager;
use Yii;
use yii\filters\ContentNegotiator;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\Response;
use App\Domains\Models\Domain;
use App\Domains\Models\Tld;

class DomainsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];
        return $behaviors;
    }

    /**
     * Get domain with prices
     *
     * @return DomainDto[]
     * @throws BadRequestHttpException
     */
    public function actionCheck(): array
    {
        $params = new GetDomainPricesParams();
        if (!$params->load(Yii::$app->request->get(), '') || !$params->validate()) {
            throw new BadRequestHttpException();
        }

        /** @var EntityManager $entityManager */
        $entityManager = Yii::$container->get(EntityManager::class);

        $tlds = $entityManager->getRepository(Tld::class)->findAll();

        $possibleDomains = Domains::fromNameAndTlds($params->search, ObjectArrays::createFieldArray($tlds, 'tld'));
        $existsDomains = $entityManager->getRepository(Domain::class)->findByDomain($possibleDomains);
        $existsDomains = ObjectArrays::createFieldArray($existsDomains, 'domain');

        /** @var DomainDto[] $dtos */
        $dtos = [];
        foreach($tlds as $tld)
        {
            $domain = "{$params->search}.{$tld->tld}";
            $dtos[] = new DomainDto($tld->tld, $domain, $tld->price, !in_array($domain, $existsDomains, true));
        }

        return $dtos;
    }
}
