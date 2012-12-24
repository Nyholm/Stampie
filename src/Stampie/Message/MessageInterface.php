<?php

/*
 * (c) Henrik Bjornskov <henrik@bjrnskov.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Stampie\Message;

/**
 * @package Stampie
 */
interface MessageInterface
{
    /**
     * @return Identity
     */
    public function getFrom();

    /**
     * @return string
     */
    public function getSubject();

    /**
     * @return string
     */
    public function getHtml();

    /**
     * @return string
     */
    public function getText();
}