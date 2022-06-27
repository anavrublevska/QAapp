<?php
/**
 * Category controller tests.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoryControllerTest.
 */
class CategoryControllerTest extends WebTestCase
{
    /**
     * Test '/category' route.
     *
     */
    public const TEST_ROUTE = '/category';

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
     * Test route.
     *
     * @return void
     */
    public function testCategoryRoute(): void
    {
        // when
        $this->httpClient->request('GET', self::TEST_ROUTE);
        $resultStatusCode = $this->httpClient->getResponse()->getStatusCode();

        // then
        $this->assertEquals(200, $resultStatusCode);
    }

    /**
     * Test show category.
     *
     * @return void
     */
    public function testShowCategory(): void
    {
        // given
        $expectedCategory = new Category();
        $expectedCategory->setName('Category one');
        $expectedCategory->setCreatedAt(new \DateTimeImmutable('now'));
        $expectedCategory->setUpdatedAt(new \DateTimeImmutable('now'));
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $categoryRepository->save($expectedCategory);

        // when
        $this->httpClient->request('GET', '/category/' . $expectedCategory->getId());
        $result = $this->httpClient->getResponse();

        // then
        $this->assertEquals(200, $result->getStatusCode());
    }

    /**
     * Category message.
     *
     * @return void
     */
    public function testCategoryMessage(): void
    {
        // when
        $this->httpClient->request('GET', '/category');
        $result = $this->httpClient->getResponse()->getContent();

        // then
        $this->assertStringContainsString('Kategorie', $result);
    }

    /**
     * Test category header.
     */
    public function testCategoryHeaderTag(): void
    {
        // when
        $this->httpClient->request('GET', '/category');

        // then
        self::assertSelectorTextContains('html title', 'Kategorie');
        self::assertSelectorTextContains('html h1', 'Kategorie');
    }

    /**
     * Test edit category.
     * @return void
     *
     */
    public function testEditCategory(): void
    {
        // given
        $user = $this->createUser('category2');
        $this->httpClient->loginUser($user);

        $categoryRepository =
            static::getContainer()->get(CategoryRepository::class);
        $testCategory = new Category();
        $testCategory->setName('TestCategory');
        $testCategory->setCreatedAt(new DateTimeImmutable('now'));
        $testCategory->setUpdatedAt(new DateTimeImmutable('now'));
        $categoryRepository->save($testCategory);
        $testCategoryId = $testCategory->getId();
        $expectedNewCategoryName = 'TestCategoryEdit';

        $this->httpClient->request('GET', self::TEST_ROUTE . '/' .
            $testCategoryId . '/edit');

        // when
        $this->httpClient->submitForm(
            'Zapisz',
            ['category' => ['name' => $expectedNewCategoryName]]
        );

        // then
        $savedCategory = $categoryRepository->findOneById($testCategoryId);
        $this->assertEquals($expectedNewCategoryName,
            $savedCategory->getName());
    }

    /**
     * Test delete category.
     *
     * @return void
     */
    public function testDeleteCategory(): void
    {
        // given
        $user = $this->createUser('category1');
        $this->httpClient->loginUser($user);
        $categoryRepository =
            static::getContainer()->get(CategoryRepository::class);
        $testCategory = new Category();
        $testCategory->setName('TestCategoryCreated');
        $testCategory->setCreatedAt(new \DateTimeImmutable('now'));
        $testCategory->setUpdatedAt(new \DateTimeImmutable('now'));
        $categoryRepository->save($testCategory);
        $testCategoryId = $testCategory->getId();
        $this->httpClient->request('GET', self::TEST_ROUTE . '/' . $testCategoryId . '/delete');

        //when
        $this->httpClient->submitForm(
            'Usuń'
        );

        // then
        $this->assertNull($categoryRepository->findOneByName('TestCategoryCreated'));
    }
}
