<?php
namespace ZendBaseModel\Domain\Specification;

/**
 * Class OrSpecification
 * @package ZendBaseModel\Domain\Specification
 */
class OrSpecification extends CompositeSpecification
{
    /**
     * @var SpecificationInterface
     */
    private $first;

    /**
     * @var SpecificationInterface
     */
    private $second;

    /**
     * @param SpecificationInterface $first
     * @param SpecificationInterface $second
     */
    public function __construct(SpecificationInterface $first, SpecificationInterface $second)
    {
        $this->first  = $first;
        $this->second = $second;
    }

    /**
     * @param $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
    {
        return $this->first->isSatisfiedBy($candidate) || $this->second->isSatisfiedBy($candidate);
    }
}