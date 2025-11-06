<?php

namespace Magdev\Banner\Model;

use Magento\Framework\Model\AbstractModel;
use Magdev\Banner\Api\Data\BannerInterface;

class Banner extends AbstractModel implements BannerInterface
{
    protected function _construct()
    {
        $this->_init(\Magdev\Banner\Model\ResourceModel\Banner::class);
    }

    public function getId()
    {
        return $this->getData(self::BANNER_ID);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function getImageUrl()
    {
        return $this->getData(self::IMAGE_URL);
    }

    public function getStatus()
    {
        return $this->getData(self::STATUS);
    }

    public function setId($id)
    {
        return $this->setData(self::BANNER_ID, $id);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function setImageUrl($url)
    {
        return $this->setData(self::IMAGE_URL, $url);
    }

    public function setStatus($status)
    {
        return $this->setData(self::STATUS, $status);
    }
}
