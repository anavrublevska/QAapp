<?php
/**
 * Answer controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\AnswerRepository;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AnswerControllerTest.
 */

class AnswerControllerTest extends WebTestCase
{
    /**
     * Test client.
     */
    protected KernelBrowser $httpClient;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->httpClient = static::createClient();
    }

    /**
     * Create user.
     *
     * @param string $name
     * @return User User entity
     */
    protected function createUser(string $name): User
    {
        $passwordHasher = static::getContainer()->get('security.password_hasher');
        $user = new User();
        $user->setEmail($name . '@example.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword(
            $passwordHasher->hashPassword(
                $user,
                'admin123'
            )
        );
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userRepository->save($user);

        return $user;
    }

    /**
     * Create category.
     *
     * @param string $name
     * @return Category
     */
    protected function createCategory(string $name): Category
    {
        $category = new Category();
        $category->setName($name);
        $category->setCreatedAt(new \DateTimeImmutable('now'));
        $category->setUpdatedAt(new \DateTimeImmutable('now'));
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($category);

        return $category;
    }

    /**
     * Create Question.
     *
     * @param string $title
     * @param Category $category
     * @return Question
     */
    protected function createQuestion(string $title, Category $category): Question
    {
        $questionRepository =
            static::getContainer()->get(QuestionRepository::class);
        $testQuestion = new Question();
        $testQuestion->setTitle($title);
        $testQuestion->setContent('Lorem ipsum dolor sit amet');
        $testQuestion->setNickname('user11');
        $testQuestion->setEmail('user11@example.com');
        $testQuestion->setCategory($category);
        $testQuestion->setCreatedAt(new DateTimeImmutable('now'));
        $testQuestion->setUpdatedAt(new DateTimeImmutable('now'));
        $questionRepository->save($testQuestion);

        return $testQuestion;
    }

    /**
     * Test edit answer.
     *
     * @return void
     */
    public function testEditAnswer(): void
    {
        // given
        $category = $this->createCategory('Category_Four');
        $testQuestion = $this->createQuestion('TestQuestion1', $category);
        $user = $this->createUser('user111');
        $this->httpClient->loginUser($user);

        $answerRepository =
            static::getContainer()->get(AnswerRepository::class);
        $testAnswer = new Answer();
        $testAnswer->setContent('Lorem ipsum dolor sit amet');
        $testAnswer->setNickname('user111');
        $testAnswer->setBest(0);
        $testAnswer->setEmail('user111@example.com');
        $testAnswer->setQuestion($testQuestion);
        $testAnswer->setCreatedAt(new DateTimeImmutable('now'));
        $testAnswer->setUpdatedAt(new DateTimeImmutable('now'));
        $answerRepository->save($testAnswer);
        $testAnswerId = $testAnswer->getId();

        // when
        $this->httpClient->request('GET',  '/answer' . '/' .
            $testAnswerId . '/edit');
        $this->httpClient->submitForm(
            'Zapisz',
            ['answer' => ['content' => 'hello world', 'email' => 'hello@email.com', 'nickname' => 'hellouser']]
        );

        // then
        $savedAnswer = $answerRepository->findOneById($testAnswerId);
        $this->assertEquals($testAnswerId,
            $savedAnswer->getId());
    }

    /**
     * Test delete answer.
     *
     * @return void
     */
    public function testDeleteAnswer(): void
    {
        // given
        $category = $this->createCategory('Category_Five');
        $testQuestion = $this->createQuestion('TestQuestion2', $category);
        $user = $this->createUser('user222');
        $this->httpClient->loginUser($user);

        $answerRepository =
            static::getContainer()->get(AnswerRepository::class);
        $testAnswer = new Answer();
        $testAnswer->setContent('Lorem ipsum dolor sit amet');
        $testAnswer->setNickname('user111');
        $testAnswer->setBest(0);
        $testAnswer->setEmail('user111@example.com');
        $testAnswer->setQuestion($testQuestion);
        $testAnswer->setCreatedAt(new DateTimeImmutable('now'));
        $testAnswer->setUpdatedAt(new DateTimeImmutable('now'));
        $answerRepository->save($testAnswer);
        $testAnswerId = $testAnswer->getId();

        // when
        $this->httpClient->request('GET',  '/answer' . '/' .
            $testAnswerId . '/delete');
        $this->httpClient->submitForm(
            'Usuń'
        );

        // then
        $this->assertNull($answerRepository->findOneById($testAnswerId));
    }

    /**
     * Test best answer.
     *
     * @return void
     */
    public function testBestAnswer(): void
    {
        // given
        $category = $this->createCategory('Category_Seven');
        $testQuestion = $this->createQuestion('TestQuestion10', $category);
        $user = $this->createUser('user333');
        $this->httpClient->loginUser($user);

        $answerRepository =
            static::getContainer()->get(AnswerRepository::class);
        $testAnswer = new Answer();
        $testAnswer->setContent('Lorem ipsum dolor sit amet');
        $testAnswer->setNickname('user111');
        $testAnswer->setBest(1);
        $testAnswer->setEmail('user111@example.com');
        $testAnswer->setQuestion($testQuestion);
        $testAnswer->setCreatedAt(new DateTimeImmutable('now'));
        $testAnswer->setUpdatedAt(new DateTimeImmutable('now'));
        $answerRepository->save($testAnswer);
        $testAnswerId = $testAnswer->getId();

        // when
        $this->httpClient->request('GET',  '/answer' . '/' .
            $testAnswerId . '/best');
        $this->httpClient->submitForm(
            'Zapisz',
            ['answerBest' => ['best' => 1]]
        );

        // then
        $savedAnswer = $answerRepository->findOneById($testAnswerId);
        $this->assertEquals(1,
            $savedAnswer->isBest());
    }

}