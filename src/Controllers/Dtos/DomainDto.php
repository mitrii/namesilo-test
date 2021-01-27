<?php

namespace App\Controllers\Dtos;

class DomainDto
{
    use ConstructableFromArrayTrait;

    /** @var string */
    public $tld;

    /** @var string */
    public $domain;

    /** @var double */
    public $price;

    /** @var bool */
    public $available;

    /**
     * DomainDto constructor.
     * @param string $tld
     * @param string $domain
     * @param float  $price
     * @param bool   $available
     */
    public function __construct(string $tld, string $domain, float $price, bool $available)
    {
        $this->tld = $tld;
        $this->domain = $domain;
        $this->price = $price;
        $this->available = $available;
    }

}
