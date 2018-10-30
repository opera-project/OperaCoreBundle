<?php

namespace Opera\CoreBundle\Repository;

use Opera\CoreBundle\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Opera\CoreBundle\Routing\RoutingUtils;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function findOnePublishedWithoutRouteAndSlug($slug)
    {
        return $this->createQueryBuilder('p')
                    ->innerJoin('p.layout', 'l')
                    ->addSelect('l')
                    ->andWhere('p.route IS NULL')
                    ->andWhere('p.slug = :slug')
                    ->andWhere('p.status = :status')
                    ->setParameter('slug', $slug)
                    ->setParameter('status', 'published')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findAllRoutes()
    {
        return $this->createQueryBuilder('p')
                    ->andWhere('p.route IS NOT NULL')
                    ->getQuery()
                    ->getResult();
    }

    public function findOnePublishedWithRoute(string $route)
    {
        return $this->createQueryBuilder('p')
                    ->innerJoin('p.layout', 'l')
                    ->addSelect('l')
                    ->andWhere('p.route = :route')
                    ->andWhere('p.status = :status')
                    ->setParameter('route', $route)
                    ->setParameter('status', 'published')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findOnePublishedWithPatternMatch(string $pathInfo)
    {
        $pages = $this->createQueryBuilder('p')
                ->innerJoin('p.layout', 'l')
                ->addSelect('l')
                ->andWhere('p.route IS NULL')
                ->andWhere('p.is_regexp = true')
                ->andWhere('p.status = :status')
                ->setParameter('status', 'published')
                ->getQuery()
                ->getResult();

        foreach ($pages as $page) {
            // Convert to a real regexp
            if (preg_match(RoutingUtils::convertPathToRegexp($page->getSlug(), $page->getRequirements() ?? []), $pathInfo)) {
                return $page;
            }
        }
    }
}
