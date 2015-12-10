<?php
namespace ZendBaseModel\Domain\Specification;

/**
 * Class CompositeSpecification
 * @package ZendBaseModel\Domain\Specification
 */
class AndSpecification extends CompositeSpecification
{
    /**
     * @var ISpecificationInterface
     */
    private $first;

    /**
     * @var ISpecificationInterface
     */
    private $second;

    /**
     * @param ISpecificationInterface $first
     * @param ISpecificationInterface $second
     */
    public function __construct(ISpecificationInterface $first, ISpecificationInterface $second)
    {
        $this->first  = $first;
        $this->second = $second;
    }

    public function isSatisfiedBy($candidate)
    {
        return $this->first->isSatisfiedBy($candidate) && $this->second->isSatisfiedBy($candidate);
    }
}