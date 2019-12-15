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
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function actionCheck(): array
    {
        $params = new GetDomainPricesParams();
        if (!$params->load(Yii::$app->request->get(), '') || !$params->validate()) {
            throw new BadRequestHttpException();
        }

        $domainService = Yii::$container->get('DomainService');

        /** @var DomainDto[] $dtos */
        $dtos = [];
        foreach ($domainService->getDomainTldsInfo($params->search) as $domainInfo)
        {
            $dtos[] = DomainDto::fromArray($domainInfo);
        }

        return $dtos;
    }
}
