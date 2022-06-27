<?php
/**
 * Answer fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Question;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class AnswerFixtures.
 */
class AnswerFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'answer', function (int $i) {
            $answer = new Answer();
            $answer->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $answer->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $answer->setContent($this->faker->sentence);
            $answer->setBest(false);
            $answer->setEmail($this->faker->email);
            $answer->setNickname($this->faker->word);
            /** @var Question $question */
            $question = $this->getRandomReference('questions');
            $answer->setQuestion($question);

            return $answer;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     */
    public function getDependencies(): array
    {
        return [QuestionFixtures::class];
    }
}
