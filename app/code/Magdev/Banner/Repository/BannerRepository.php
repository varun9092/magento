<?php

namespace Magdev\Banner\Repository;

use Magdev\Banner\Api\BannerRepositoryInterface;
use Magdev\Banner\Api\Data\BannerInterface;
use Magdev\Banner\Model\BannerFactory;
use Magdev\Banner\Model\ResourceModel\Banner as BannerResource;

class BannerRepository implements BannerRepositoryInterface
{
    protected $bannerFactory;
    protected $resource;

    public function __construct(
        BannerFactory $bannerFactory,
        BannerResource $resource
    ) {
        $this->bannerFactory = $bannerFactory;
        $this->resource = $resource;
    }

    public function save(BannerInterface $banner)
    {
        $this->resource->save($banner);
        return $banner;
    }

    public function getById($id)
    {
        $banner = $this->bannerFactory->create();
        $this->resource->load($banner, $id);
        if (!$banner->getId()) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Banner not found'));
        }
        return $banner;
    }

    public function delete(BannerInterface $banner)
    {
        $this->resource->delete($banner);
        return true;
    }

    public function deleteById($id)
    {
        $banner = $this->getById($id);
        return $this->delete($banner);
    }
}
