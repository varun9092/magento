<?php
namespace Magdev\Banner\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\UrlInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    const URL_PATH_EDIT = 'magdev_banner/banner/edit';
    const URL_PATH_DELETE = 'magdev_banner/banner/delete';

    /** @var UrlInterface */
    protected $urlBuilder;

    /**
     * Constructor
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Add action links to each row
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['banner_id'])) {
                    $name = $this->getData('name');

                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_EDIT,
                            ['banner_id' => $item['banner_id']]
                        ),
                        'label' => __('Edit'),
                        'hidden' => false,
                    ];

                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_DELETE,
                            ['banner_id' => $item['banner_id']]
                        ),
                        'label' => __('Delete'),
                        'confirm' => [
                            'title' => __('Delete "${ $.$data.title }"'),
                            'message' => __('Are you sure you want to delete "${ $.$data.title }"?'),
                        ],
                        'post' => true,
                    ];
                }
            }
        }

        return $dataSource;
    }
}
