<?php

namespace Magdev\Banner\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use Magdev\Banner\Repository\BannerRepository;
use Magdev\Banner\Model\ResourceModel\Banner as BannerResource;
use Magdev\Banner\Model\BannerFactory;
use Magdev\Banner\Model\Banner;
use Magdev\Banner\Api\Data\BannerInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class BannerRepositoryTest extends TestCase
{
    private $bannerResourceMock;
    private $bannerFactoryMock;
    private $bannerMock;
    private $repository;

    protected function setUp(): void
    {
        $this->bannerResourceMock = $this->createMock(BannerResource::class);
        $this->bannerFactoryMock = $this->createMock(BannerFactory::class);
        $this->bannerMock = $this->createMock(Banner::class);

        $this->repository = new BannerRepository(
            $this->bannerFactoryMock,
            $this->bannerResourceMock
        );

        /*$bannerFactoryMock = $this->createMock(\Magdev\Banner\Model\BannerFactory::class);
        $this->repository = new BannerRepository($bannerFactoryMock);*/
    }

    public function testSave()
    {
        $this->bannerResourceMock->expects($this->once())
            ->method('save')
            ->with($this->bannerMock);

        $result = $this->repository->save($this->bannerMock);
        $this->assertSame($this->bannerMock, $result);
    }

    public function testGetByIdSuccess()
    {
        $noteId = 123;

        $this->bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn($noteId);

        $this->bannerFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->bannerMock);

        $this->bannerResourceMock->expects($this->once())
            ->method('load')
            ->with($this->bannerMock, $noteId);

        $result = $this->repository->getById($noteId);
        $this->assertSame($this->bannerMock, $result);
    }

    public function testGetByIdThrowsException()
    {
        $noteId = 1;

        $this->bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->bannerFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->bannerMock);

        $this->bannerResourceMock->expects($this->once())
            ->method('load')
            ->with($this->bannerMock, $noteId);

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('Banner not found');

        $this->repository->getById($noteId);
    }

    public function testDelete()
    {
        $this->bannerResourceMock->expects($this->once())
            ->method('delete')
            ->with($this->bannerMock);

        $result = $this->repository->delete($this->bannerMock);
        $this->assertTrue($result);
    }

    public function testDeleteById()
    {
        $noteId = 123;

        $this->bannerMock->expects($this->once())
            ->method('getId')
            ->willReturn($noteId);

        $this->bannerFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->bannerMock);

        $this->bannerResourceMock->expects($this->once())
            ->method('load')
            ->with($this->bannerMock, $noteId);

        $this->bannerResourceMock->expects($this->once())
            ->method('delete')
            ->with($this->bannerMock);

        $result = $this->repository->deleteById($noteId);
        $this->assertTrue($result);
    }

    public function testGetByIdReturnsBanner()
    {
        $bannerId = 123;

        // Mock the Banner model
        $bannerMock = $this->createMock(\Magdev\Banner\Model\Banner::class);
        $bannerMock->method('getId')->willReturn($bannerId);

        // Mock the BannerFactory to return the mocked banner
        $bannerFactoryMock = $this->getMockBuilder(\Magdev\Banner\Model\BannerFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();
        $bannerFactoryMock->method('create')->willReturn($bannerMock);

        // Mock the BannerRepository and inject the factory
        $repository = new \Magdev\Banner\Repository\BannerRepository(
            $bannerFactoryMock, // Add other dependencies if needed
            $this->bannerResourceMock
        );

        $result = $repository->getById($bannerId);

        $this->assertInstanceOf(\Magdev\Banner\Model\Banner::class, $result);
        $this->assertEquals($bannerId, $result->getId());
    }
    public function testSavePersistsBanner()
    {
        $bannerMock = $this->createMock(\Magdev\Banner\Model\Banner::class);

        $resourceMock = $this->getMockBuilder(\Magdev\Banner\Model\ResourceModel\Banner::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['save'])
            ->getMock();
        $resourceMock->expects($this->once())
            ->method('save')
            ->with($bannerMock);

        $repository = new \Magdev\Banner\Repository\BannerRepository(
            /* factory */            $this->createMock(\Magdev\Banner\Model\BannerFactory::class),
            /* resource */ $resourceMock
        );

        $this->assertSame($bannerMock, $repository->save($bannerMock));
    }
    public function testDeleteRemovesBanner()
    {
        $bannerMock = $this->createMock(\Magdev\Banner\Model\Banner::class);

        $resourceMock = $this->getMockBuilder(\Magdev\Banner\Model\ResourceModel\Banner::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['delete'])
            ->getMock();
        $resourceMock->expects($this->once())
            ->method('delete')
            ->with($bannerMock);

        $repository = new \Magdev\Banner\Repository\BannerRepository(
            $this->createMock(\Magdev\Banner\Model\BannerFactory::class),
            $resourceMock
        );

        $this->assertTrue($repository->delete($bannerMock));
    }
}
