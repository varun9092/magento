<?php
namespace Magdev\Banner\Model\Resolver;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CustomProductDetails implements ResolverInterface
{
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (!isset($args['sku'])) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlInputException(__('SKU is required'));
        }

        try {
            $product = $this->productRepository->get($args['sku']);

            return [
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'price' => (float) $product->getPrice(),
                'description' => $product->getDescription()
            ];
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException(
                __('Product with SKU "%1" not found', $args['sku'])
            );
        }
    }
}
