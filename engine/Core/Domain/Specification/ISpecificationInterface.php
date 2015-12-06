<?php
namespace Core\Domain\Specification;

/**
 * Interface ISpecificationInterface
 * @package Core\Domain\Specification
 */
interface ISpecificationInterface
{

    /**
     * @param $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate);

    public function AndSpecification(ISpecificationInterface $spec);

    public function OrSpecification(ISpecificationInterface $spec);

    public function NotSpecification();
}