<?php

namespace Magdev\Banner\Api;

use Magdev\Banner\Api\Data\BannerInterface;

interface BannerRepositoryInterface
{
    public function save(BannerInterface $banner);
    public function getById($id);
    public function delete(BannerInterface $banner);
    public function deleteById($id);
}
