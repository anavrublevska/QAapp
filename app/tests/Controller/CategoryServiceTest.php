<?php
/**
 * Category Service Test.
 */

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class CategoryServiceTest.
 */
class CategoryServiceTest extends WebTestCase
{
    /**
     * Category service.
     */
    private ?CategoryService $categoryService;


    /**
     * @return void
     */
    public function setUp(): void
    {
        $container = static::getContainer();
        $this->categoryService = $container->get(CategoryService::class);
    }

    /**
     * Test GetPaginatedList.
     *
     * @return void
     */
    public function testGetPaginatedList(): void
    {
        // given
        $page = 1;
        $dataSetSize = 3;
        $expectedResultSize = 9;
        $categoryRepository =
            static::getContainer()->get(CategoryRepository::class);

        $i = 0;
        while ($i < $dataSetSize) {
            $category = new Category();
            $category->setName('Categoryx' . $i);
            $category->setCreatedAt(new \DateTimeImmutable('now'));
            $category->setUpdatedAt(new \DateTimeImmutable('now'));
            $categoryRepository->save($category);

            ++$i;
        }
        // when
        $result = $this->categoryService->getPaginatedList($page);

        // then
        $this->assertEquals($expectedResultSize, count($result));

    }
}