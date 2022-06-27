<?php
/**
 * UserControllerTest.
 */

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class UserControllerTest.
 */
class UserControllerTest extends WebTestCase
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
     * Test show user.
     *
     * @return void
     */
    public function testShowUser(): void
    {
        // given
        $user = $this->createUser('user_a');
        $this->httpClient->loginUser($user);

        // when
        $this->httpClient->request('GET', '/account/' . $user->getId());
        $result = $this->httpClient->getResponse();

        // then
        $this->assertEquals(200, $result->getStatusCode());

    }

    /**
     * Test edit email.
     *
     * @return void
     */
    public function testEditEmailUser(): void
    {
        // given
        $user = $this->createUser('user_b');
        $this->httpClient->loginUser($user);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $newEmail = 'newemail_b@example.com';

        // when
        $this->httpClient->request('GET', '/account/' .
            $user->getId() . '/edit/email');

        $this->httpClient->submitForm(
            'Zapisz',
            ['user' => ['email' => $newEmail]]
        );

        // then
        $savedUser = $userRepository->findOneById($user->getId());
        $this->assertEquals($newEmail, $savedUser->getEmail());
    }

}
