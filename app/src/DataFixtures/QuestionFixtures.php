<?php
/**
 * Question fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Question;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

//use App\Entity\User;

/**
 * Class QuestionFixtures.
 */
class QuestionFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'questions', function (int $i) {
            $question = new Question();
            $question->setTitle($this->faker->sentence);
            $question->setCreatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $question->setUpdatedAt(
                DateTimeImmutable::createFromMutable(
                    $this->faker->dateTimeBetween('-100 days', '-1 days')
                )
            );
            $question->setContent($this->faker->sentence);
            $question->setEmail($this->faker->email);
            $question->setNickname($this->faker->word);
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $question->setCategory($category);

            return $question;
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
        return [CategoryFixtures::class];
    }
}
