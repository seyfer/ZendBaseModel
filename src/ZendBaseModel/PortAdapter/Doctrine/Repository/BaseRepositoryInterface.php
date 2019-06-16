<?php
/**
 * Created by PhpStorm.
 * User: seyfer
 * Date: 4/19/16
 */

namespace ZendBaseModel\PortAdapter\Doctrine\Repository;

use Doctrine\ORM\Query;
use Zend\Paginator\Paginator as ZendPaginator;


/**
 * Description of BaseRepository
 *
 * @author seyfer
 */
interface BaseRepositoryInterface
{
    /**
     * @param array $params
     * @param array $orderBy
     * @return array
     */
    public function findByParams($params = [], $orderBy = []);

    /**
     * @param array $params
     * @param array $orderBy
     * @return mixed
     */
    public function findOneByParams($params = [], $orderBy = []);

    /**
     * find all with limit
     * also this works: findBy($criteria, $order, $limit)
     *
     * @param $limit
     * @return array
     * @throws \Exception
     */
    public function findAllLimit($limit);

    /**
     * get count by query
     *
     * @return int
     */
    public function getCount();

    /**
     * get count by query
     *
     * @param array $params
     * @param array $orderBy
     * @return int
     */
    public function getCountByParams($params = [], $orderBy = []);

    /**
     * find something with paginator
     *
     * @param        $pageCount
     * @param        $currentPage
     * @param array $params
     * @param array $orderBy
     * @return ZendPaginator
     */
    public function findWithPaginator($pageCount, $currentPage, $params = [], $orderBy = []);

    /**
     * adapter for paginator
     *
     * @param  $query
     * @return \DoctrineORMModule\Paginator\Adapter\DoctrinePaginator
     */
    public function getPaginationAdapterDoctrine($query);

    /**
     * standard search query
     *
     * @param array $params
     * @param array $orderBy
     * @return Query
     */
    public function getSearchQuery($params = [], $orderBy = []);

    /**
     * get paginated Doctrine result
     *
     * @param int $pageNum
     * @param int $currentPage
     * @param     $query
     * @return ZendPaginator
     */
    public function getPaginatedDoctrine($pageNum = 5, $currentPage = 1, $query = null);
}
