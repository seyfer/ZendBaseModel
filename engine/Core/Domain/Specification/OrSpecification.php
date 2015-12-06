<?php
namespace Core\Domain\Specification;

/**
 * Class OrSpecification
 * @package Core\Domain\Specification
 */
class OrSpecification extends CompositeSpecification
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

    /**
     * @param $candidate
     * @return bool
     */
    public function isSatisfiedBy($candidate)
    {
        return $this->first->isSatisfiedBy($candidate) || $this->second->isSatisfiedBy($candidate);
    }
}