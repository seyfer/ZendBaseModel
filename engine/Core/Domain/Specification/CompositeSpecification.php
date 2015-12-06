<?php
namespace Core\Domain\Specification;

/**
 * Class CompositeSpecification
 * @package Core\Domain\Specification
 */
abstract class CompositeSpecification implements ISpecificationInterface
{
    abstract public function isSatisfiedBy($candidate);

    /**
     * @param ISpecificationInterface $spec
     * @return AndSpecification
     */
    public function AndSpecification(ISpecificationInterface $spec)
    {
        return new AndSpecification($this, $spec);
    }

    /**
     * @param ISpecificationInterface $spec
     * @return OrSpecification
     */
    public function OrSpecification(ISpecificationInterface $spec)
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