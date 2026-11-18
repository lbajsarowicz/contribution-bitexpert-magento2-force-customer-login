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

/**
 * Customer session with an explicit authentication URL accessor for test doubles.
 */
class CustomerSessionDouble extends \Magento\Customer\Model\Session
{
    /**
     * @param string $url
     * @return $this
     */
    public function setBeforeAuthUrl($url)
    {
        return $this->setData('before_auth_url', $url);
    }
}
