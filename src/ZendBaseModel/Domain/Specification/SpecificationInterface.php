<?php

namespace ZendBaseModel\Domain\Specification;

/**
 * Interface SpecificationInterface
 * @package ZendBaseModel\Domain\Specification
 */
interface SpecificationInterface
{

    /**
     * @param $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate);

    public function AndSpecification(SpecificationInterface $spec);

    public function OrSpecification(SpecificationInterface $spec);

    public function NotSpecification();
}
