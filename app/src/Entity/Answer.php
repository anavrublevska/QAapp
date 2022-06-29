<?php
/**
 * Answer entity.
 */

namespace App\Entity;

use App\Repository\AnswerRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Answer.
 */
#[ORM\Entity(repositoryClass: AnswerRepository::class)]
#[ORM\Table(name: 'ztp_answers')]
class Answer
{
    /**
     * Primary key.
     *
     * @var int
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    /**
     * Created at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'create')]
    private ?DateTimeImmutable $createdAt;

    /**
     * Updated at.
     *
     * @var DateTimeImmutable|null
     */
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\Type(DateTimeImmutable::class)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?DateTimeImmutable $updatedAt;

    /**
     * Content.
     *
     * @var string
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\NotBlank]
    private string $content;

    /**
     * @var bool
     */
    #[ORM\Column(type: 'boolean', nullable: false)]
    private bool $best;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private string $email;

    /**
     * @var string
     */
    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    private string $nickname;

    /**
     * Relation to question.
     *
     * @var Question
     */
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    private Question $question;

    /**
     * @return int|null int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return DateTimeImmutable|null dateTime
     */
    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Setter for createdAt.
     *
     * @param DateTimeImmutable|null $createdAt createdAt
     */
    public function setCreatedAt(?DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable|null dateTime
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Setter for updatedAt.
     *
     * @param DateTimeImmutable|null $updatedAt updatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string|null string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * Set content.
     *
     * @param string $content content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return bool|null bool
     */
    public function isBest(): ?bool
    {
        return $this->best;
    }

    /**
     * Setter for isBest, $best param = true or false.
     *
     * @param bool $best best
     */
    public function setBest(bool $best): void
    {
        $this->best = $best;
    }

    /**
     * @return string|null string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Setter for email.
     *
     * @param string $email email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null string
     */
    public function getNickname(): ?string
    {
        return $this->nickname;
    }

    /**
     * Set nickname.
     *
     * @param string $nickname nickname
     */
    public function setNickname(string $nickname): void
    {
        $this->nickname = $nickname;
    }

    /**
     * @return Question|null Question
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    /**
     * Set question (relation).
     *
     * @param Question|null $question question
     */
    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }
}
