<?php

namespace ZendBaseModel\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator as ZendPaginator;
use Doctrine\ORM\Query;

/**
 * Description of BaseRepository
 *
 * @author seyfer
 */
class BaseRepository extends EntityRepository
{

    /**
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;

    protected function processSearchParams($params = [])
    {
        if ($params) {

        }
    }

    /**
     * find all with limit
     * also this works: findBy($criteria, $order, $limit)
     *
     * @param $limit
     * @return array
     * @throws \Exception
     */
    public function findAllLimit($limit)
    {
        if (!$limit) {
            throw new \Exception(__METHOD__ . " limit param required");
        }

        $this->queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $this->queryBuilder->select('e')
                           ->from($this->getEntityName(), 'e');

        $this->queryBuilder->setMaxResults($limit);

        $query = $this->queryBuilder->getQuery();

        return $query->getResult();
    }

    /**
     * get count by query
     *
     * @return int
     */
    public function getCount()
    {
        $this->queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $this->queryBuilder->select('COUNT(e.id) as entryCount')
                           ->from($this->getEntityName(), 'e');

        $query = $this->queryBuilder->getQuery();

        return $query->getArrayResult()[0]['entryCount'];
    }

    /**
     * get count by query
     *
     * @return int
     */
    public function getCountByParams($params = [], $orderBy = [])
    {
        //        \Zend\Debug\Debug::dump($params, __METHOD__);

        $this->queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $this->queryBuilder->select('DISTINCT e.id, COUNT(e.id) as entryCount')
                           ->from($this->getEntityName(), 'e');

        $this->processSearchParams($params);

        $this->addOrder($orderBy);

        $query = $this->queryBuilder->getQuery();

        //        \Zend\Debug\Debug::dump($query->getDQL());

        return $query->getArrayResult()[0]['entryCount'];
    }

    /**
     * find something with paginator
     *
     * @param        $pageCount
     * @param        $currentPage
     * @param array  $params
     * @param array  $orderBy
     * @return ZendPaginator
     */
    public function findWithPaginator($pageCount, $currentPage, $params = [], $orderBy = [])
    {
        $query = $this->getSearchQuery($params, $orderBy);

        $paginator = $this->getPaginatedDoctrine($pageCount, $currentPage, $query);

        return $paginator;
    }

    /**
     * adapter for paginator
     *
     * @param  $query
     * @return \DoctrineORMModule\Paginator\Adapter\DoctrinePaginator
     */
    public function getPaginationAdapterDoctrine($query)
    {
        return new PaginatorAdapter(new ORMPaginator($query));
    }

    /**
     * standard search query
     *
     * @param array $params
     * @return Query
     */
    public function getSearchQuery($params = [], $orderBy = [])
    {
        $this->queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $this->queryBuilder->select('e')->from($this->getEntityName(), 'e');

        $this->processSearchParams($params);

        $this->addOrder($orderBy);

        $query = $this->queryBuilder->getQuery();

        return $query;
    }

    /**
     * add order to query
     *
     * @param array $orderBy
     * @internal param $
     */
    protected function addOrder($orderBy = [])
    {
        if ($orderBy) {
            foreach ($orderBy as $column => $direction) {
                if (!in_array($direction, ["ASC", "DESC"])) {
                    continue;
                }

                $this->queryBuilder->orderBy("e.$column", $direction);
            }
        }
    }

    /**
     * get paginated Doctrine result
     *
     * @param int $pageNum
     * @param int $currentPage
     * @param $query
     * @return ZendPaginator
     */
    public function getPaginatedDoctrine($pageNum = 5, $currentPage = 1, $query = null)
    {
        $paginator = new ZendPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $paginator->setDefaultItemCountPerPage($pageNum);

        if ($currentPage) {
            $paginator->setCurrentPageNumber($currentPage);
        }

        return $paginator;
    }

}
