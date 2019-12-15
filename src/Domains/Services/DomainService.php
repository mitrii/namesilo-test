<?php
/**
 * Poject: namesilo-test
 * User: mitrii
 * Date: 12.15.2019
 * Time: 15:04
 * Original File Name: DomainService.php
 */

namespace App\Domains\Services;


use App\Controllers\Dtos\DomainDto;
use App\Domains\Models\Domain;
use App\Domains\Models\Tld;
use App\Utils\Domains;
use App\Utils\ObjectArrays;
use Doctrine\ORM\EntityManager;

class DomainService
{
    public $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTlds()
    {
        return $this->em->getRepository(Tld::class)->findAll();
    }

    /**
     * @param string $name
     * @param array $tlds
     * @return string[]
     */
    public function getPossibleDomains($name, $tlds): array
    {
        $domains = Domains::fromNameAndTlds($name, $tlds);
        return array_combine($tlds, $domains);
    }

    /**
     * @param string[] $domains
     * @return array
     */
    public function getExistsDomains($domains): array
    {
        $existsDomains = $this->em->getRepository(Domain::class)->findByDomain($domains);
        return ObjectArrays::createFieldArray($existsDomains, 'domain');
    }

    public function getDomainTldsInfo($domain)
    {
        $tlds = $this->getTlds();
        $possibleDomains = $this->getPossibleDomains($domain, ObjectArrays::createFieldArray($tlds, 'tld'));
        $existsDomains = $this->getExistsDomains($possibleDomains);

        foreach($tlds as $tld)
        {
            yield [
                'tld' => $tld->tld,
                'domain' => $possibleDomains[$tld->tld],
                'price' => $tld->price,
                'available' => !in_array($possibleDomains[$tld->tld], $existsDomains, true)
            ];
        }
    }
}