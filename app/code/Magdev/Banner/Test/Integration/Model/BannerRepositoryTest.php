<?php

declare(strict_types=1);

namespace Magdev\Banner\Test\Integration\Model;

use PHPUnit\Framework\TestCase;
use Magento\TestFramework\Helper\Bootstrap;
use Magdev\Banner\Api\BannerRepositoryInterface;
use Magdev\Banner\Api\Data\BannerInterface;
use Magdev\Banner\Model\BannerFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class BannerRepositoryTest extends TestCase
{
    /** @var BannerRepositoryInterface */
    private $bannerRepository;

    /** @var BannerFactory */
    private $bannerFactory;

    protected function setUp(): void
    {
        $this->bannerRepository = Bootstrap::getObjectManager()->get(BannerRepositoryInterface::class);
        $this->bannerFactory = Bootstrap::getObjectManager()->get(BannerFactory::class);
    }

    public function testSaveGetAndDeleteBanner(): void
    {
        // Step 1: Create a new Banner
        /** @var BannerInterface $banner */
        $banner = $this->bannerFactory->create();
        $banner->setTitle('Test Banner Title');
        $banner->setImageUrl('https://example.com/test.jpg');
        $banner->setIsActive(true);

        // Step 2: Save it
        $savedBanner = $this->bannerRepository->save($banner);
        $this->assertNotNull($savedBanner->getId());
        $this->assertEquals('Test Banner Title', $savedBanner->getTitle());

        $bannerId = $savedBanner->getId();

        // Step 3: Load it from DB
        $loadedBanner = $this->bannerRepository->getById($bannerId);
        $this->assertEquals($bannerId, $loadedBanner->getId());
        $this->assertEquals('https://example.com/test.jpg', $loadedBanner->getImageUrl());

        // Step 4: Delete it
        $this->bannerRepository->deleteById($bannerId);

        // Step 5: Ensure it's deleted
        $this->expectException(NoSuchEntityException::class);
        $this->bannerRepository->getById($bannerId);
    }
}
