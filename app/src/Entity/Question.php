<?php
/**
 * Question entity.
 */
namespace App\Entity;

use App\Repository\QuestionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Question.
 */
#[ORM\Entity(repositoryClass: QuestionRepository::class)]
#[ORM\Table(name: 'ztp_questions')]
class Question
{
    /**
     * Primary key.
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt;

    /**
     * Updated at.
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt;

    /**
     * Question title.
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private ?string $title;

    /**
     * Content.
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank]
    private ?string $content = null;

    /**
     * Author's email.
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $email = null;

    /**
     * Author's nickname.
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private ?string $nickname = null;

    /**
     * Relation to category.
     * @var Category
     */
    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private Category $category;

    #[ORM\OneToMany(mappedBy: 'question', targetEntity: Answer::class)]
    private $answers;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * Getter for id.
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for createdAt.
     * @return DateTimeImmutable|null
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for createdAt.
     * @param DateTimeImmutable|null $createdAt
     *
     * @return void
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Getter for updatedAt.
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     * @param DateTimeImmutable|null $updatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Getter for question title.
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Setter for question title.
     * @param string|null $title
     *
     * @return void
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * Getter for content.
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Setter for content.
     * @param string|null $content
     *
     * @return void
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }

    /**
     * Get email.
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email.
     * @param string $email
     *
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get nickname.
     * @return string|null
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Set nickname.
     * @param string $nickname
     *
     * @return void
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * Get relation to category.
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Setter for category relation.
     * @param Category|null $category
     *
     * @return void
     */
    public function setCategory(?Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return Collection<int, Answer>
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    /**
     * Add answer.
     *
     * @param Answer $answer
     *
     * @return $this
     */
    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    /**
     * Remove answer.
     *
     * @param Answer $answer
     *
     * @return $this
     */
    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->removeElement($answer)) {
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
