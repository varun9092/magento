<?php

namespace Magdev\Banner\Api\Data;

interface BannerInterface
{
    const BANNER_ID = 'banner_id';
    const TITLE = 'title';
    const IMAGE_URL = 'image_url';
    const STATUS = 'status';

    public function getId();
    public function getTitle();
    public function getImageUrl();
    public function getStatus();

    public function setId($id);
    public function setTitle($title);
    public function setImageUrl($url);
    public function setStatus($status);
}
