<?php

/*
 * This file is part of the Force Login module for Magento2.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BitExpert\ForceCustomerLogin\Test\Unit\Model;

/**
 * Session with explicit accessors for test doubles.
 */
class SessionDouble extends \BitExpert\ForceCustomerLogin\Model\Session
{
    /**
     * @return mixed
     */
    public function getAfterLoginReferer()
    {
        return $this->getData('after_login_referer');
    }

    /**
     * @param string $referer
     * @return $this
     */
    public function setAfterLoginReferer($referer)
    {
        return $this->setData('after_login_referer', $referer);
    }
}
