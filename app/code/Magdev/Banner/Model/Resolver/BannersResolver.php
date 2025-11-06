<?php
namespace Magdev\Banner\Model\Resolver;

use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magdev\Banner\Model\ResourceModel\Banner\CollectionFactory;

class BannersResolver implements ResolverInterface
{
    protected $bannerCollectionFactory;

    public function __construct(
        CollectionFactory $bannerCollectionFactory
    ) {
        $this->bannerCollectionFactory = $bannerCollectionFactory;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $collection = $this->bannerCollectionFactory->create();
        $collection->addFieldToSelect(['banner_id', 'title', 'image_url', 'status']);

        $result = [];
        
        if (!empty($args['filter'])) {
			$filters = $args['filter'];

			if (isset($filters['status'])) {
				$collection->addFieldToFilter('status', $filters['status']);
			}

			if (isset($filters['from_date'])) {
				$collection->addFieldToFilter('created_at', ['lteq' => $filters['from_date']]);
			}

			if (isset($filters['to_date'])) {
				$collection->addFieldToFilter('created_at', ['gteq' => $filters['to_date']]);
			}
		} 
		foreach ($collection as $banner) {
			$result[] = [
				'banner_id' => (int)$banner->getBannerId(),
				'title' => $banner->getTitle(),
				'image_url' => $banner->getImageUrl(),
				'status' => $banner->getStatus()
			];
		}
 
        return $result;
    }
}
