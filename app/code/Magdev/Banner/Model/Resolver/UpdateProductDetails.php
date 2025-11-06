<?php
namespace Magdev\Banner\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Magento\Catalog\Api\ProductRepositoryInterface;
//use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class UpdateProductDetails implements ResolverInterface
{
    protected $productRepository;
	protected $productFactory;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
        //$this->productFactory = $productFactory;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        $input = $args['input'];

        if (!isset($input['sku'])) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlInputException(__('SKU is required.'));
        }
        
        try {
            $product = $this->productRepository->get($input['sku']);

            if (isset($input['name'])) {
                $product->setName($input['name']);
            }
            if (isset($input['price'])) {
                $product->setPrice($input['price']);
            }
            if (isset($input['description'])) {
                $product->setDescription($input['description']);
            }

            $this->productRepository->save($product);

            return [
                'sku' => $product->getSku(),
                'name' => $product->getName(),
                'price' => (float)$product->getPrice(),
                'description' => $product->getDescription()
            ];

        } catch (NoSuchEntityException $e) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException(
                __('Product with SKU "%1" not found', $input['sku'])
            );
        } catch (\Exception $e) {
            throw new \Magento\Framework\GraphQl\Exception\GraphQlInputException(
                __('Could not update product: %1', $e->getMessage())
            );
        }
    }
}

