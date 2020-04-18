<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="Un compte existe déjà avec ce pseudo")
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 */
	private $username;

	/**
	 * @ORM\Column(type="json", nullable=true)
	 */
	private $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 * @Assert\Length(min=6, minMessage="Le mot de passe doit avoir 6 charactères minimum")
	 */
	private $password;

	private $oldPassword;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="author", orphanRemoval=true)
	 */
	private $comments;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $name;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $lastname;

	/**
	 * @ORM\Column(type="string", length=255)
	 */
	private $email;

	/**
	 * @ORM\Column(type="date", nullable=true)
	 */
	private $birthday;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $createdAt;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $avatar;

	/**
	 * @ORM\ManyToMany(targetEntity="App\Entity\Badge", inversedBy="users")
	 */
	private $badges;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Topic", mappedBy="author")
	 */
	private $topics;

	/**
	 * @ORM\Column(type="boolean", options={"default": "1"})
	 */
	private $isActive = 1;

	/**
	 * @ORM\Column(type="boolean", options={"default": "0"})
	 */
	private $isBlocked = 0;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $gender;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Pokedex", mappedBy="user", orphanRemoval=true)
	 */
	private $pokedex;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @ORM\Column(type="integer", nullable=true, options={"default": "6"})
	 */
	private $pokeball = 6;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
		$this->badges = new ArrayCollection();
		$this->topics = new ArrayCollection();
		$this->createdAt = new DateTime();
		$this->pokedex = new ArrayCollection();

		$random_img = \random_int(1, 99);

		if ($this->gender == 'woman') {
			$this->avatar = "https://randomuser.me/api/portraits/women/$random_img.jpg";
		} else {
			$this->avatar = "https://randomuser.me/api/portraits/men/$random_img.jpg";
		}
	}

	public function __toString()
	{
		return $this->getUsername();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
	{
		return (string) $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
	{
		return (string) $this->password;
	}

	public function setPassword(string $password): self
	{
		if (!empty($password)) {

			$this->password = $password;
		}

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
	{
		// not needed when using the "bcrypt" algorithm in security.yaml
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	/**
	 * @return Collection|Comment[]
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}

	public function addComment(Comment $comment): self
	{
		if (!$this->comments->contains($comment)) {
			$this->comments[] = $comment;
			$comment->setAuthor($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): self
	{
		if ($this->comments->contains($comment)) {
			$this->comments->removeElement($comment);
			// set the owning side to null (unless already changed)
			if ($comment->getAuthor() === $this) {
				$comment->setAuthor(null);
			}
		}

		return $this;
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

	public function getLastname(): ?string
	{
		return $this->lastname;
	}

	public function setLastname(string $lastname): self
	{
		$this->lastname = $lastname;

		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getBirthday(): ?\DateTimeInterface
	{
		return $this->birthday;
	}

	public function setBirthday(?\DateTimeInterface $birthday): self
	{
		$this->birthday = $birthday;

		return $this;
	}

	public function getCreatedAt(): ?\DateTimeInterface
	{
		return $this->createdAt;
	}

	public function setCreatedAt(\DateTimeInterface $createdAt): self
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	public function getAvatar(): ?string
	{
		return $this->avatar;
	}

	public function setAvatar(?string $avatar): self
	{
		$this->avatar = $avatar;

		return $this;
	}

	/**
	 * @return Collection|Badge[]
	 */
	public function getBadges(): Collection
	{
		return $this->badges;
	}

	public function addBadge(Badge $badge): self
	{
		if (!$this->badges->contains($badge)) {
			$this->badges[] = $badge;
		}

		return $this;
	}

	public function removeBadge(Badge $badge): self
	{
		if ($this->badges->contains($badge)) {
			$this->badges->removeElement($badge);
		}

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
			$topic->setAuthor($this);
		}

		return $this;
	}

	public function removeTopic(Topic $topic): self
	{
		if ($this->topics->contains($topic)) {
			$this->topics->removeElement($topic);
			// set the owning side to null (unless already changed)
			if ($topic->getAuthor() === $this) {
				$topic->setAuthor(null);
			}
		}

		return $this;
	}

	public function getIsActive(): ?bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): self
	{
		$this->isActive = $isActive;

		return $this;
	}

	public function getIsBlocked(): ?bool
	{
		return $this->isBlocked;
	}

	public function setIsBlocked(bool $isBlocked): self
	{
		$this->isBlocked = $isBlocked;

		return $this;
	}

	public function getGender(): ?string
	{
		return $this->gender;
	}

	public function setGender(?string $gender): self
	{
		$this->gender = $gender;

		return $this;
	}

	/**
	 * @return Collection|Pokedex[]
	 */
	public function getPokedex(): Collection
	{
		return $this->pokedex;
	}

	public function addPokedex(Pokedex $pokedex): self
	{
		if (!$this->pokedex->contains($pokedex)) {
			$this->pokedex[] = $pokedex;
			$pokedex->setUser($this);
		}

		return $this;
	}

	public function removePokedex(Pokedex $pokedex): self
	{
		if ($this->pokedex->contains($pokedex)) {
			$this->pokedex->removeElement($pokedex);
			// set the owning side to null (unless already changed)
			if ($pokedex->getUser() === $this) {
				$pokedex->setUser(null);
			}
		}

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): self
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get the value of oldPassword
	 */
	public function getOldPassword()
	{
		return $this->oldPassword;
	}

	/**
	 * Set the value of oldPassword
	 *
	 * @return  self
	 */
	public function setOldPassword($oldPassword)
	{
		$this->oldPassword = $oldPassword;

		return $this;
	}

	public function getPokeball(): ?int
	{
		return $this->pokeball;
	}

	public function setPokeball(?int $pokeball): self
	{
		$this->pokeball = $pokeball;

		return $this;
	}
}
