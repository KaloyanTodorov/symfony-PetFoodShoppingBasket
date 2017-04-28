<?php

namespace PetFoodShoppingBasketBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

/**
 * Product
 *
 * @ORM\Table(name="products")
 * @ORM\Entity(repositoryClass="PetFoodShoppingBasketBundle\Repository\ProductRepository")
 */
class Product
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal", precision=10, scale=2)
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_on", type="datetime", nullable=false, options={"default" = "CURRENT_TIMESTAMP"})
     */
    private $createdOn;

    /**
     * @var Review[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="PetFoodShoppingBasketBundle\Entity\Review", mappedBy="product")
     */
    private $reviews;

    /**
     * @var Tag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="PetFoodShoppingBasketBundle\Entity\Tag")
     * @ORM\JoinTable(name="products_tags",
     *     joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")}
     *     )
     */
    private $tags;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="PetFoodShoppingBasketBundle\Entity\Category", inversedBy="products")
     */
    private $category;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="PetFoodShoppingBasketBundle\Entity\User", inversedBy="products")
     */
    private $user;

    /**
     * @var Stock
     * @ORM\OneToOne(targetEntity="PetFoodShoppingBasketBundle\Entity\Stock", mappedBy="product")
     */
    private $stock;

    /**
     * @var
     * @ORM\Column(name="quantity", type="integer", length=5)
     */
    private $quantity;

    /**
     * @var
     * @ORM\Column(name="is_on_promotion", type="boolean")
     */
    private $isOnPromotion;

    public function __construct()
    {
        $this->createdOn = new \DateTime();
        $this->reviews = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param mixed $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return ArrayCollection|Review[]
     */
    public function getReviews()
    {
        return $this->reviews;
    }

    /**
     * @param ArrayCollection|Review[] $reviews
     */
    public function setReviews($reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * @return Tag[]|ArrayCollection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getIsOnPromotion()
    {
        return $this->isOnPromotion;
    }

    /**
     * @param mixed $isOnPromotion
     */
    public function setIsOnPromotion($isOnPromotion)
    {
        $this->isOnPromotion = $isOnPromotion;
    }


}

