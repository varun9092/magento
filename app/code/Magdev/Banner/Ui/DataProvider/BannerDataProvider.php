<?php
namespace Magdev\Banner\Ui\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Magdev\Banner\Model\ResourceModel\Banner\CollectionFactory;

class BannerDataProvider extends AbstractDataProvider
{
    protected $loadedData;

    /**
     * Constructor
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data for UI Component grid
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            //return $this->loadedData;
        }

        $items = $this->collection->getItems();
        
        //echo $this->collection->getSize();
        
        //exit;

        $this->loadedData = [
            'totalRecords' => $this->collection->getSize(),
            'items' => []
        ];

        foreach ($items as $item) {
            $this->loadedData['items'][] = $item->getData();
        }

        return $this->loadedData;
    }
}
