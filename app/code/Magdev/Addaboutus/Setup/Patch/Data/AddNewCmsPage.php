<?php
namespace Magdev\Addaboutus\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\ResourceModel\Page as PageResource;
use Magento\Store\Model\Store;

class AddNewCmsPage implements DataPatchInterface
{
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * @var PageResource
     */
    private $pageResource;

    public function __construct(
        PageFactory $pageFactory,
        PageResource $pageResource
    ) {
        $this->pageFactory = $pageFactory;
        $this->pageResource = $pageResource;
    }

    public function apply()
    {
        $identifier = 'about-page';
        $title = 'My Custom CMS Page';
        $content = <<<HTML
<h1>Welcome to About us CMS Page</h1>
<p>This page was created programmatically via a Magento 2 Data Patch.</p>
<p>latest content</p>
HTML;

        // Try to load existing page by identifier
        $page = $this->pageFactory->create();
        $this->pageResource->load($page, $identifier, 'identifier');

        // If not exists, create it
       // if (!$page->getId()) {
            $page->setTitle($title)
                ->setIdentifier($identifier)
                ->setIsActive(true)
                ->setPageLayout('1column')
                ->setStores([Store::DEFAULT_STORE_ID])
                ->setContentHeading('Custom Page')
                ->setContent($content)
                ->setMetaTitle('My Custom Page')
                ->setMetaDescription('This is a custom CMS page added via setup patch.')
                ->setSortOrder(0);

            $this->pageResource->save($page);
        //}

        return $this;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
