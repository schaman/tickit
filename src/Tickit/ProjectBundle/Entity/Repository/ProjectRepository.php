<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Tickit\CoreBundle\Entity\Repository\FilterableRepositoryInterface;
use Tickit\CoreBundle\Filters\Collection\FilterCollection;

/**
 * Project entity repository.
 *
 * Provides methods for retrieving Project related data from the data layer
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectRepository extends EntityRepository implements FilterableRepositoryInterface
{
    /**
     * Finds results based off a set of filters.
     *
     * @param FilterCollection $filters The filter collection
     *
     * @return mixed
     */
    public function findByFilters(FilterCollection $filters)
    {
        $projectsQ = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p')
                      ->from('TickitProjectBundle:Project', 'p');

        $filters->applyToQuery($projectsQ);

        return $projectsQ->getQuery()->execute();
    }
}
