<?php
/**
 * This file is part of the Catalyst Swoole Foundation.
 *
 * (c)2017 Matthias Kaschubowski and the Catalyst Collective
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Catalyst\Swoole\Servants;


use Catalyst\Servant\RepositoryServant;

/**
 * Class FactoryServant
 *
 * @package Catalyst\Swoole\Servants
 */
class FactoryServant extends RepositoryServant
{
    /**
     * returns the reflection of the provided closure.
     *
     * @param \Closure $closure
     * @return \ReflectionFunction
     */
    public function reflect(\Closure $closure): \ReflectionFunction
    {
        return new \ReflectionFunction($closure);
    }
}