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

use BitExpert\ForceCustomerLogin\Api\Data\Collection\WhitelistEntryCollectionFactoryInterface;
use BitExpert\ForceCustomerLogin\Api\Data\WhitelistEntryFactoryInterface;
use BitExpert\ForceCustomerLogin\Api\Repository\WhitelistRepositoryInterface;
use BitExpert\ForceCustomerLogin\Model\ResourceModel\WhitelistEntry\Collection;
use BitExpert\ForceCustomerLogin\Model\WhitelistEntry;
use BitExpert\ForceCustomerLogin\Model\WhitelistEntrySearchResultInterfaceFactory;
use BitExpert\ForceCustomerLogin\Repository\WhitelistRepository;
use Magento\Framework\Api\Filter;
use Magento\Framework\Api\Search\FilterGroup;
use Magento\Framework\Api\SearchCriteria;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\StoreManager;
use PHPUnit\Framework\TestCase;

/**
 * Class WhitelistRepositoryUnitTest
 *
 * @package BitExpert\ForceCustomerLogin\Test\Unit\Repository
 */
class WhitelistRepositoryUnitTest extends TestCase
{
    /**
     * @test
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function testClassExists()
    {
        $this->assertTrue(class_exists(WhitelistRepository::class));
    }

    /**
     * @test
     * @depends testClassExists
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testClassExists')]
    public function testConstructor()
    {
        $whitelistRepository = new WhitelistRepository(
            $this->getWhitelistEntryFactory(),
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        // check if mandatory interfaces are implemented
        $classInterfaces = class_implements($whitelistRepository);
        $this->assertContains(WhitelistRepositoryInterface::class, $classInterfaces);
    }

    /**
     * @return WhitelistEntryFactoryInterface
     */
    private function getWhitelistEntryFactory()
    {
        return $this->createMock(WhitelistEntryFactoryInterface::class);
    }

    /**
     * @return WhitelistEntryCollectionFactoryInterface
     */
    private function getWhitelistEntryCollectionFactory()
    {
        return $this->createMock(WhitelistEntryCollectionFactoryInterface::class);
    }

    /**
     * @return StoreManager
     */
    private function getStoreManager()
    {
        return $this->getMockBuilder(StoreManager::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return WhitelistEntrySearchResultInterfaceFactory
     */
    private function getWhitelistEntrySearchResultInterfaceFactory()
    {
        return $this->getMockBuilder(WhitelistEntrySearchResultInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * Run test of creating a new entry without existing entity
     *
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function testEntryCreationWithoutExistingEntity()
    {
        $entityId = null;
        $label = 'foobar';
        $urlRule = '/foobar';
        $storeId = 0;
        $strategy = 'default';

        $expectedWhitelistEntry = $this->createMock(WhitelistEntry::class);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('load')
            ->with($label, 'label')
            ->willReturnSelf();
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getLabel')
            ->willReturn($label);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getUrlRule')
            ->willReturn($urlRule);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getStrategy')
            ->willReturn($strategy);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getEditable')
            ->willReturn(true);

        $whitelistEntryFactory = $this->getWhitelistEntryFactory();
        $whitelistEntryFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);
        $whitelistEntryFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);

        $whitelistRepository = new WhitelistRepository(
            $whitelistEntryFactory,
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $resultWhitelistEntity = $whitelistRepository->createEntry($entityId, $label, $urlRule, $strategy, $storeId);

        $this->assertEquals($expectedWhitelistEntry, $resultWhitelistEntity);
    }

    /**
     * Run test of creating a new entry with existing entity
     *
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function testEntryCreationWithExistingEntity()
    {
        $entityId = 42;
        $label = 'foobar';
        $urlRule = '/foobar';
        $storeId = 0;
        $strategy = 'default';

        $expectedWhitelistEntry = $this->createMock(WhitelistEntry::class);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('load')
            ->with($entityId)
            ->willReturnSelf();
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getEditable')
            ->willReturn(true);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('setEditable')
            ->with(true);
        // validation
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getLabel')
            ->willReturn($label);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getUrlRule')
            ->willReturn($urlRule);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getStrategy')
            ->willReturn($strategy);


        $whitelistEntryFactory = $this->getWhitelistEntryFactory();
        $whitelistEntryFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);

        $whitelistRepository = new WhitelistRepository(
            $whitelistEntryFactory,
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $resultWhitelistEntity = $whitelistRepository->createEntry($entityId, $label, $urlRule, $strategy, $storeId);

        $this->assertEquals($expectedWhitelistEntry, $resultWhitelistEntity);
    }

    /**
     * Run test of creating a new entry with existing entity being not editable
     *
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function testEntryCreationWithExistingEntityFailsDueToNonEditable()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Whitelist entry not editable.');

        $entityId = 42;
        $label = 'foobar';
        $urlRule = '/foobar';
        $storeId = 0;
        $strategy = 'default';

        $expectedWhitelistEntry = $this->createMock(WhitelistEntry::class);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('load')
            ->with($entityId)
            ->willReturnSelf();
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->atLeastOnce())
            ->method('getEditable')
            ->willReturn(false);

        $whitelistEntryFactory = $this->getWhitelistEntryFactory();
        $whitelistEntryFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);

        $whitelistRepository = new WhitelistRepository(
            $whitelistEntryFactory,
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $whitelistRepository->createEntry($entityId, $label, $urlRule, $strategy, $storeId);
    }

    /**
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function deleteEntityFailsDueToNonEditable()
    {
        $entityId = 42;

        $expectedWhitelistEntry = $this->createMock(WhitelistEntry::class);
        $expectedWhitelistEntry->expects($this->once())
            ->method('load')
            ->willReturnSelf();
        $expectedWhitelistEntry->expects($this->once())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->once())
            ->method('getEditable')
            ->willReturn(false);

        $whitelistEntryFactory = $this->getWhitelistEntryFactory();
        $whitelistEntryFactory->expects($this->once())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);

        $whitelistRepository = new WhitelistRepository(
            $whitelistEntryFactory,
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $this->assertFalse($whitelistRepository->deleteEntry($entityId));
    }

    /**
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function deleteEntitySucceeds()
    {
        $entityId = 42;

        $expectedWhitelistEntry = $this->createMock(WhitelistEntry::class);
        $expectedWhitelistEntry->expects($this->once())
            ->method('load')
            ->willReturnSelf();
        $expectedWhitelistEntry->expects($this->once())
            ->method('getId')
            ->willReturn($entityId);
        $expectedWhitelistEntry->expects($this->once())
            ->method('getEditable')
            ->willReturn(true);
        $expectedWhitelistEntry->expects($this->once())
            ->method('delete');

        $whitelistEntryFactory = $this->getWhitelistEntryFactory();
        $whitelistEntryFactory->expects($this->once())
            ->method('create')
            ->willReturn($expectedWhitelistEntry);

        $whitelistRepository = new WhitelistRepository(
            $whitelistEntryFactory,
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $this->assertTrue($whitelistRepository->deleteEntry($entityId));
    }

    /**
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function getCollectionSuccessfully()
    {
        $storeId = 222;
        $store = $this->createMock(StoreInterface::class);
        $store->expects($this->once())
            ->method('getId')
            ->willReturn($storeId);

        $storeManager = $this->getStoreManager();
        $storeManager->expects($this->once())
            ->method('getStore')
            ->willReturn($store);

        $expectedCollection = $this->getMockBuilder(Collection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $expectedCollection->expects($this->once())
            ->method('addFieldToFilter')
            ->with(
                'store_id',
                [
                    'in' => [
                        WhitelistRepositoryInterface::ROOT_STORE_ID,
                        $storeId
                    ]
                ]
            );
        $expectedCollection->expects($this->once())
            ->method('load')
            ->willReturnSelf();

        $collectionFactory = $this->getWhitelistEntryCollectionFactory();
        $collectionFactory->expects($this->once())
            ->method('create')
            ->willReturn($expectedCollection);

        $whitelistRepository = new WhitelistRepository(
            $this->getWhitelistEntryFactory(),
            $collectionFactory,
            $storeManager,
            $this->getWhitelistEntrySearchResultInterfaceFactory()
        );

        $this->assertEquals($expectedCollection, $whitelistRepository->getCollection());
    }

    /**
     * @test
     * @depends testConstructor
     */
    #[\PHPUnit\Framework\Attributes\Test]
    #[\PHPUnit\Framework\Attributes\Depends('testConstructor')]
    public function getListSuccessfully()
    {
        $filter = $this->getMockBuilder(Filter::class)
            ->disableOriginalConstructor()
            ->getMock();
        $filter->expects($this->exactly(2))
            ->method('getConditionType')
            ->willReturn('foo');
        $filter->expects($this->once())
            ->method('getField')
            ->willReturn('bar');
        $filter->expects($this->once())
            ->method('getValue')
            ->willReturn('baz');

        $filterGroup = $this->getMockBuilder(FilterGroup::class)
            ->disableOriginalConstructor()
            ->getMock();
        $filterGroup->expects($this->once())
            ->method('getFilters')
            ->willReturn([$filter]);

        $searchCriteria = $this->getMockBuilder(SearchCriteria::class)
            ->disableOriginalConstructor()
            ->getMock();
        $searchCriteria->expects($this->once())
            ->method('getFilterGroups')
            ->willReturn([$filterGroup]);
        $searchCriteria->expects($this->once())
            ->method('getCurrentPage')
            ->willReturn(3);
        $searchCriteria->expects($this->once())
            ->method('getPageSize')
            ->willReturn(42);

        $expectedSearchResult = $this->getMockBuilder(WhitelistSearchResultDoubleInterface::class)
            ->onlyMethods([
                'getItems',
                'setItems',
                'getSearchCriteria',
                'setSearchCriteria',
                'getTotalCount',
                'setTotalCount',
                'addFieldToFilter',
                'setCurPage',
                'setPageSize'
            ])
            ->getMock();
        $expectedSearchResult->expects($this->once())
            ->method('addFieldToFilter')
            ->with('bar', ['foo' => 'baz']);
        $expectedSearchResult->expects($this->once())
            ->method('setCurPage')
            ->with(3);
        $expectedSearchResult->expects($this->once())
            ->method('setPageSize')
            ->with(42);

        $searchResultFactory = $this->getWhitelistEntrySearchResultInterfaceFactory();
        $searchResultFactory->expects($this->once())
            ->method('create')
            ->willReturn($expectedSearchResult);

        $whitelistRepository = new WhitelistRepository(
            $this->getWhitelistEntryFactory(),
            $this->getWhitelistEntryCollectionFactory(),
            $this->getStoreManager(),
            $searchResultFactory
        );

        $this->assertEquals($expectedSearchResult, $whitelistRepository->getList($searchCriteria));
    }
}
