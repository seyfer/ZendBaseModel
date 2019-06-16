<?php

namespace ZendBaseModel\Domain\Specification;

/**
 * Class CompositeSpecification
 * @package ZendBaseModel\Domain\Specification
 */
abstract class CompositeSpecification implements SpecificationInterface
{
    abstract public function isSatisfiedBy($candidate);

    /**
     * @param SpecificationInterface $spec
     * @return AndSpecification
     */
    public function AndSpecification(SpecificationInterface $spec)
    {
        return new AndSpecification($this, $spec);
    }

    /**
     * @param SpecificationInterface $spec
     * @return OrSpecification
     */
    public function OrSpecification(SpecificationInterface $spec)
    {
        return new OrSpecification($this, $spec);
    }

    /**
     * @return NotSpecification
     */
    public function NotSpecification()
    {
        return new NotSpecification($this);
    }
}
