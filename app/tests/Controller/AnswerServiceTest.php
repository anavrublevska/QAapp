<?php
/**
 * AnswerServiceTest.
 */

namespace App\Tests\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Question;
use App\Repository\AnswerRepository;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use App\Service\AnswerService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class AnswerServiceTest extends WebTestCase
{
    /**
     * Answer service.
     */
    private ?AnswerService $answerService;

    /**
     * @return void void
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->answerService = $container->get(AnswerService::class);
    }

    /**
     * TestPaginatedList.
     *
     * @return void void
     */
    public function testGetPaginatedList(): void
    {
        $category = new Category();
        $category->setName('CategoryService');
        $category->setCreatedAt(new \DateTimeImmutable('now'));
        $category->setUpdatedAt(new \DateTimeImmutable('now'));
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);
        // given

        $questionRepository =
            static::getContainer()->get(QuestionRepository::class);
        $testQuestion = new Question();
        $testQuestion->setTitle('TestQuestion');
        $testQuestion->setContent('Lorem ipsum dolor sit amet');
        $testQuestion->setNickname('user11');
        $testQuestion->setEmail('user11@example.com');
        $testQuestion->setCategory($category);
        $testQuestion->setCreatedAt(new DateTimeImmutable('now'));
        $testQuestion->setUpdatedAt(new DateTimeImmutable('now'));
        $questionRepository->save($testQuestion);
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 5;
        $answerRepository =
            static::getContainer()->get(AnswerRepository::class);

        $i = 0;
        while ($i < $dataSetSize) {
            $answer = new Answer();
            $answer->setContent('Lorem ipsum dolor sit amet');
            $answer->setNickname('user'.$i);
            $answer->setBest(0);
            $answer->setEmail('user'.$i.'@example.com');
            $answer->setQuestion($testQuestion);
            $answer->setCreatedAt(new DateTimeImmutable('now'));
            $answer->setUpdatedAt(new DateTimeImmutable('now'));
            $answerRepository->save($answer);

            ++$i;
        }
        // when
        $result = $this->answerService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, count($result));
    }
}
