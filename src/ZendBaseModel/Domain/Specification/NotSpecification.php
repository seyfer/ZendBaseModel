<?php
namespace ZendBaseModel\Domain\Specification;

/**
 * Class OrSpecification
 * @package ZendBaseModel\Domain\Specification
 */
class NotSpecification extends CompositeSpecification
{
    /**
     * @var SpecificationInterface
     */
    private $first;

    /**
     * @param SpecificationInterface $first
     */
    public function __construct(SpecificationInterface $first)
    {
        $this->first = $first;
    }

    /**
     * @param $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
    {
        return !$this->first->isSatisfiedBy($candidate);
    }
}