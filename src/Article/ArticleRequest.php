<?php

namespace App\Article;


use App\Entity\Article;
use App\Entity\User;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

class ArticleRequest
{

    private $id;

    /**
     * @Assert\NotBlank(message="N'oubliez pas le titre.")
     * @Assert\Length(
     *     max=255,
     *     maxMessage="Votre titre est trop long. Pas plus de {{ limit }} caractères."
     * )
     */
    private $title;
    private $slug;

    /**
     * @Assert\NotBlank(message="N'oubliez pas de rédiger votre article.")
     */
    private $content;

    /**
     * @Assert\Image(mimeTypesMessage="Votre image n'est pas reconnue.",
     *     maxSize="2M", maxSizeMessage="Votre image est trop lourde.")
     */
    private $featuredImage;
    private $imageUrl;
    private $special;
    private $spotlight;
    private $createdDate;

    /**
     * @Assert\NotNull(message="N'oubliez pas de choisir une catégorie.")
     */
    private $category;
    private $user;

    /**
     * ArticleRequest constructor.
     * @param $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->createdDate = new \DateTime();
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getFeaturedImage()
    {
        return $this->featuredImage;
    }

    /**
     * @param mixed $featuredImage
     */
    public function setFeaturedImage($featuredImage)
    {
        $this->featuredImage = $featuredImage;
    }

    /**
     * @return mixed
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * @param mixed $special
     */
    public function setSpecial($special)
    {
        $this->special = $special;
    }

    /**
     * @return mixed
     */
    public function getSpotlight()
    {
        return $this->spotlight;
    }

    /**
     * @param mixed $spotlight
     */
    public function setSpotlight($spotlight)
    {
        $this->spotlight = $spotlight;
    }

    /**
     * @return mixed
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * @param mixed $createdDate
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * @return mixed
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
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Créer un ArticleRequest depuis un Article de Entity
     * @param Article $article
     * @param Packages $packages
     * @param string $assetsDirectory
     * @return ArticleRequest
     * @internal param Package|Packages $package
     * @internal param $assetsDirectory
     * @internal param Package $package
     */
    public static function createFromArticle(Article $article, Packages $packages, string $assetsDirectory): self
    {
        $ar = new self($article->getUser());
        $ar->id = $article->getId();
        $ar->title = $article->getTitle();
        $ar->slug = $article->getSlug();
        $ar->content = $article->getContent();
        $ar->featuredImage = new File($assetsDirectory . '/' . $article->getFeaturedImage());
        $ar->imageUrl = $packages->getUrl('images/product/'. $article->getFeaturedImage());
        $ar->special = $article->getSpecial();
        $ar->spotlight = $article->getSpotlight();
        $ar->createdDate = $article->getCreatedDate();
        $ar->category = $article->getCategory();

        return $ar;
    }
}