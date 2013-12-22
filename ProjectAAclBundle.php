<?php

/*
 * This file is part of the ProjectA AclBundle.
 *
 * (c) Project A Ventures GmbH & Co. KG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ProjectA\Bundle\AclBundle;

use ProjectA\Bundle\AclBundle\DependencyInjection\ProjectAAclExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Daniel Tschinder <daniel.tschinder@project-a.com>
 */
class ProjectAAclBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ProjectAAclExtension();
    }
}
