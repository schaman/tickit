<?php

namespace Tickit\ProjectBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DateTime;

/**
 * Project entity repository.
 *
 * Provides methods for retrieving Project related data from the data layer
 *
 * @author James Halsall <james.t.halsall@googlemail.com>
 */
class ProjectRepository extends EntityRepository
{
    /**
     * Returns a collection of projects that match the given criteria
     *
     * @param array $filters An array of filters used to filter the result
     *
     * @return mixed
     */
    public function findProjects(array $filters = array())
    {
        $projectsQ = $this->getEntityManager()
                      ->createQueryBuilder()
                      ->select('p')
                      ->from('TickitProjectBundle:Project', 'p');

        foreach ($filters as $column => $value) {
            if (is_string($value)) {
                $projectsQ->where(sprintf('%s LIKE :%s', $column, $column));
                $projectsQ->setParameter($column, $value);
            }
        }

        return $projectsQ->getQuery()->execute();
    }

}
