<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Daniel Tschinder
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle;

use ProjectA\Bundle\AclBundle\DependencyInjection\ProjectAAclExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Daniel Tschinder <daniel@tschinder.de>
 */
class ProjectAAclBundle extends Bundle
{
    /**
     * @return ProjectAAclExtension
     */
    public function getContainerExtension()
    {
        return new ProjectAAclExtension();
    }
}
