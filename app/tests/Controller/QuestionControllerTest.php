<?php
/**
 * Question controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Question;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class QuestionControllerTest.
 */
class QuestionControllerTest extends WebTestCase
{
    /**
     * Test '/question' route.
     *
     */
    public const TEST_ROUTE = '/question';

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
        $user->setEmail($name.'@example.com');
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
     * Test route.
     *
     * @return void
     */
    public function testQuestionRoute(): void
    {
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        $this->assertEquals(302, $resultStatusCode);
    }



    /**
     * Test show.
     * @return void
     */
    public function testShowQuestion(): void
    {
        // given
        $category = $this->createCategory('Category_One');
        $user = $this->createUser('user1');
        $this->httpClient->loginUser($user);
        $questionRepository =
            static::getContainer()->get(QuestionRepository::class);
        $testQuestion = new Question();
        $testQuestion->setTitle('TestQuestion');
        $testQuestion->setContent('Lorem ipsum dolor sit amet');
        $testQuestion->setNickname('userone');
        $testQuestion->setEmail('userone@example.com');
        $testQuestion->setCategory($category);
        $testQuestion->setCreatedAt(new DateTimeImmutable('now'));
        $testQuestion->setUpdatedAt(new DateTimeImmutable('now'));
        $questionRepository->save($testQuestion);

        // when
        $this->httpClient->request('GET', '/question/' . $testQuestion->getId());
        $result = $this->httpClient->getResponse();

        // then
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Test edit question.
     *
     * @return void
     */
    public function testEditQuestion(): void
    {
        // given
        $category = $this->createCategory('Category_Two');
        $user = $this->createUser('user2');
        $this->httpClient->loginUser($user);

        $questionRepository =
            static::getContainer()->get(QuestionRepository::class);
        $testQuestion = new Question();
        $testQuestion->setTitle('TestQuestion');
        $testQuestion->setContent('Lorem ipsum dolor sit amet');
        $testQuestion->setNickname('usertwo');
        $testQuestion->setEmail('usertwo@example.com');
        $testQuestion->setCategory($category);
        $testQuestion->setCreatedAt(new DateTimeImmutable('now'));
        $testQuestion->setUpdatedAt(new DateTimeImmutable('now'));
        $questionRepository->save($testQuestion);
        $testQuestionId = $testQuestion->getId();
        $expectedNewQuestionTitle = 'TestQuestionEdit';

        $this->httpClient->request('GET', self::TEST_ROUTE . '/' .
            $testQuestionId . '/edit');

        // when
        $this->httpClient->submitForm(
            'Zapisz',
            ['question' => ['title' => $expectedNewQuestionTitle]]
        );

        // then
        $savedQuestion = $questionRepository->findOneById($testQuestionId);
        $this->assertEquals($expectedNewQuestionTitle,
            $savedQuestion->getTitle());
    }

    /**
     * Test delete question.
     *
     * @return void
     */
    public function testDeleteQuestion(): void
    {
        // given
        $category = $this->createCategory('Category_Three');
        $user = $this->createUser('user3');
        $this->httpClient->loginUser($user);
        $questionRepository =
            static::getContainer()->get(QuestionRepository::class);
        $testQuestion = new Question();
        $testQuestion->setTitle('TestQuestion');
        $testQuestion->setContent('Lorem ipsum dolor sit amet');
        $testQuestion->setNickname('userthree');
        $testQuestion->setEmail('userthree@example.com');
        $testQuestion->setCategory($category);
        $testQuestion->setCreatedAt(new DateTimeImmutable('now'));
        $testQuestion->setUpdatedAt(new DateTimeImmutable('now'));
        $questionRepository->save($testQuestion);
        $testQuestionId = $testQuestion->getId();
        $this->httpClient->request('GET', self::TEST_ROUTE . '/' . $testQuestionId . '/delete');

        //when
        $this->httpClient->submitForm(
            'Usuń'
        );

        // then
        $this->assertNull($questionRepository->findOneByTitle('TestQuestionCreated'));
    }
}
