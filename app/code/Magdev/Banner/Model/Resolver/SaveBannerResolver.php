<?php
namespace Magdev\Banner\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magdev\Banner\Model\BannerFactory;
use Magdev\Banner\Model\ResourceModel\Banner as BannerResource;

class SaveBannerResolver implements ResolverInterface
{
    protected $bannerFactory;
    protected $bannerResource;

    public function __construct(
        BannerFactory $bannerFactory,
        BannerResource $bannerResource
    ) {
        $this->bannerFactory = $bannerFactory;
        $this->bannerResource = $bannerResource;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $input = $args['input'];

        $banner = $this->bannerFactory->create();

        if (!empty($input['banner_id'])) {
            $this->bannerResource->load($banner, $input['banner_id']);
            if (!$banner->getId()) {
                throw new \Exception(__('Banner with ID %1 not found.', $input['banner_id']));
            }
        }

        $banner->setTitle($input['title']);
        $banner->setImageUrl($input['image_url']);
        $banner->setStatus($input['status'] ?? 1);
        $banner->setCreatedAt($input['created_at'] ?? null);
        $banner->setUpdatedAt($input['updated_at'] ?? null);

        $this->bannerResource->save($banner);

        return [
            'banner_id' => (int)$banner->getId(),
            'title' => $banner->getTitle(),
            'image_url' => $banner->getImageUrl(),
            'link' => $banner->getLink()
        ];
    }
}
