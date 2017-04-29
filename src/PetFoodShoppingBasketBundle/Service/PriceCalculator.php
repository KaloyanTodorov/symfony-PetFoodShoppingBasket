<?php
namespace PetFoodShoppingBasketBundle\Service;

use Doctrine\ORM\EntityManager;
use PetFoodShoppingBasketBundle\Entity\Promotion;
use PetFoodShoppingBasketBundle\Entity\Product;


class PriceCalculator
{
    /** @var  PromotionManager */
    protected $manager;

    public function __construct(PromotionManager $manager) {
        $this->manager= $manager;
    }


    /**
     * @param Product $product
     *
     * @return float
     */
    public function calculate($product)
    {
        $category = $product->getCategory();

        $promotion = $this->manager->getGeneralPromotion();

        if($this->manager->hasCategoryPromotion($category)){
            $promotion = $this->manager->getCategoryPromotion($category);
        }

        return $product->getPrice() - $product->getPrice() * ($promotion / 100);
    }
}