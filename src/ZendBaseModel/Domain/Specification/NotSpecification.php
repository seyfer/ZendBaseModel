<?php
namespace ZendBaseModel\Domain\Specification;

/**
 * Class OrSpecification
 * @package ZendBaseModel\Domain\Specification
 */
class NotSpecification extends CompositeSpecification
{
    /**
     * @var ISpecificationInterface
     */
    private $first;

    /**
     * @param ISpecificationInterface $first
     */
    public function __construct(ISpecificationInterface $first)
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