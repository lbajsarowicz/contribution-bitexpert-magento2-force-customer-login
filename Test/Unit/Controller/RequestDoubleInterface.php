<?php

/*
 * This file is part of the Force Login module for Magento2.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BitExpert\ForceCustomerLogin\Test\Unit\Controller;

use Magento\Framework\App\RequestInterface;

/**
 * Request operations used by the login check and router.
 */
interface RequestDoubleInterface extends RequestInterface
{
    /**
     * @return bool
     */
    public function isPost();

    /**
     * @param bool $flag
     * @return $this
     */
    public function setDispatched($flag = true);
}
