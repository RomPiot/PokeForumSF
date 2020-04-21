<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="category")
	 */
	private $topics;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $icon;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="categories")
	 */
	private $parentCategory;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Category", mappedBy="parentCategory")
	 */
	private $categories;

	public function __construct()
	{
		$this->topics = new ArrayCollection();
		$this->categories = new ArrayCollection();
	}

	public function __toString()
	{
		return $this->getName();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection|Topic[]
	 */
	public function getTopics(): Collection
	{
		return $this->topics;
	}

	public function addTopic(Topic $topic): self
	{
		if (!$this->topics->contains($topic)) {
			$this->topics[] = $topic;
			$topic->setCategory($this);
		}

		return $this;
	}

	public function removeTopic(Topic $topic): self
	{
		if ($this->topics->contains($topic)) {
			$this->topics->removeElement($topic);
			// set the owning side to null (unless already changed)
			if ($topic->getCategory() === $this) {
				$topic->setCategory(null);
			}
		}

		return $this;
	}

	public function getIcon(): ?string
	{
		return $this->icon;
	}

	public function setIcon(?string $icon): self
	{
		$this->icon = $icon;

		return $this;
	}

	public function getParentCategory(): ?self
	{
		return $this->parentCategory;
	}

	public function setParentCategory(?self $parentCategory): self
	{
		$this->parentCategory = $parentCategory;

		return $this;
	}

	/**
	 * @return Collection|self[]
	 */
	public function getCategories(): Collection
	{
		return $this->categories;
	}

	public function addCategory(self $category): self
	{
		if (!$this->categories->contains($category)) {
			$this->categories[] = $category;
			$category->setParentCategory($this);
		}

		return $this;
	}

	public function removeCategory(self $category): self
	{
		if ($this->categories->contains($category)) {
			$this->categories->removeElement($category);
			// set the owning side to null (unless already changed)
			if ($category->getParentCategory() === $this) {
				$category->setParentCategory(null);
			}
		}

		return $this;
	}
}
