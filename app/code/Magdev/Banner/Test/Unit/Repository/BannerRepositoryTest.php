<?php

namespace Magdev\Banner\Test\Unit\Repository;

use PHPUnit\Framework\TestCase;
use Magdev\Banner\Repository\BannerRepository;
use Magdev\Banner\Model\Banner;
use Magdev\Banner\Model\BannerFactory;
use Magdev\Banner\Model\ResourceModel\Banner as BannerResource;
use Magento\Framework\Exception\NoSuchEntityException;

class BannerRepositoryTest extends TestCase
{
    private $bannerFactoryMock;
    private $bannerResourceMock;
    private $bannerRepository;

    protected function setUp(): void
    {
        $this->bannerFactoryMock = $this->getMockBuilder(BannerFactory::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMock();

        $this->bannerResourceMock = $this->getMockBuilder(BannerResource::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['load', 'save', 'delete'])
            ->getMock();

        $this->bannerRepository = new BannerRepository(
            $this->bannerFactoryMock,
            $this->bannerResourceMock
        );
    }

    public function testGetByIdReturnsBanner()
    {
        $bannerId = 123;

        $bannerMock = $this->createMock(Banner::class);
        $bannerMock->method('getId')->willReturn($bannerId);

        $this->bannerFactoryMock->method('create')->willReturn($bannerMock);
        $this->bannerResourceMock->expects($this->once())
            ->method('load')
            ->with($bannerMock, $bannerId);

        $result = $this->bannerRepository->getById($bannerId);

        $this->assertInstanceOf(Banner::class, $result);
        $this->assertEquals($bannerId, $result->getId());
    }

    public function testGetByIdThrowsException()
    {
        $bannerId = 456;

        $bannerMock = $this->createMock(Banner::class);
        $bannerMock->method('getId')->willReturn(null);

        $this->bannerFactoryMock->method('create')->willReturn($bannerMock);
        $this->bannerResourceMock->expects($this->once())
            ->method('load')
            ->with($bannerMock, $bannerId);

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('Banner not found');

        $this->bannerRepository->getById($bannerId);
    }

    public function testSavePersistsBanner()
    {
        $bannerMock = $this->createMock(Banner::class);

        $this->bannerResourceMock->expects($this->once())
            ->method('save')
            ->with($bannerMock);

        $result = $this->bannerRepository->save($bannerMock);

        $this->assertSame($bannerMock, $result);
    }

    public function testDeleteRemovesBanner()
    {
        $bannerMock = $this->createMock(Banner::class);

        $this->bannerResourceMock->expects($this->once())
            ->method('delete')
            ->with($bannerMock);

        $result = $this->bannerRepository->delete($bannerMock);

        $this->assertTrue($result);
    }

    public function testDeleteByIdDeletesBanner()
    {
        $bannerId = 789;

        // Mock getById() to return a valid banner
        $bannerMock = $this->createMock(Banner::class);
        $bannerMock->method('getId')->willReturn($bannerId);

        // Inject a partial mock of BannerRepository to mock getById
        $repositoryMock = $this->getMockBuilder(BannerRepository::class)
            ->setConstructorArgs([
                $this->bannerFactoryMock,
                $this->bannerResourceMock
            ])
            ->onlyMethods(['getById'])
            ->getMock();

        $repositoryMock->expects($this->once())
            ->method('getById')
            ->with($bannerId)
            ->willReturn($bannerMock);

        $this->bannerResourceMock->expects($this->once())
            ->method('delete')
            ->with($bannerMock);

        $result = $repositoryMock->deleteById($bannerId);

        $this->assertTrue($result);
    }

    public function testDeleteByIdThrowsExceptionIfNotFound()
    {
        $bannerId = 1010;

        $repositoryMock = $this->getMockBuilder(BannerRepository::class)
            ->setConstructorArgs([
                $this->bannerFactoryMock,
                $this->bannerResourceMock
            ])
            ->onlyMethods(['getById'])
            ->getMock();

        $repositoryMock->expects($this->once())
            ->method('getById')
            ->with($bannerId)
            ->willThrowException(
                new NoSuchEntityException(__('Banner not found'))
            );

        $this->expectException(NoSuchEntityException::class);
        $this->expectExceptionMessage('Banner not found');

        $repositoryMock->deleteById($bannerId);
    }
}
