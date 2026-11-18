<?php

/*
 * This file is part of the Force Login module for Magento2.
 *
 * (c) bitExpert AG
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BitExpert\ForceCustomerLogin\Test\Unit\Repository;

use BitExpert\ForceCustomerLogin\Api\Data\Collection\WhitelistEntrySearchResultInterface;

/**
 * Search result collection operations used by the repository.
 */
interface WhitelistSearchResultDoubleInterface extends WhitelistEntrySearchResultInterface
{
    /**
     * @param string $field
     * @param mixed $condition
     * @return $this
     */
    public function addFieldToFilter($field, $condition);

    /**
     * @param int $page
     * @return $this
     */
    public function setCurPage($page);

    /**
     * @param int $size
     * @return $this
     */
    public function setPageSize($size);
}
