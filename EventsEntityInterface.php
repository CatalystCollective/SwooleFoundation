<?php
/**
 * This file is part of the Catalyst Swoole Foundation.
 *
 * (c)2017 Matthias Kaschubowski and the Catalyst Collective
 *
 * This code is licensed under the MIT license,
 * a copy of the license is stored at the project root.
 */

namespace Catalyst\Swoole;


/**
 * Interface EventsEntityInterface
 *
 * @package Catalyst\Swoole
 */
interface EventsEntityInterface
{
    /**
     * creates a generator of events as a data stream
     *
     * Event Name => callback
     *
     * @return \Generator
     */
    public function streamEvents(): \Generator;
}