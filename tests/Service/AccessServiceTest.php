<?php

namespace PriNikTests\FrontTest\Service;

use Doctrine\ORM\EntityManager;
use PriNikApp\FrontTest\Service\AccessService;
use PHPUnit\Framework\TestCase;

class AccessServiceTest extends TestCase
{
    protected AccessService $accessService;
    protected array $post;
    protected int $uid;
    public function setUp(): void
    {
        $container = require_once 'src/bootstrap.php';
        $em = $container->get(EntityManager::class);
        $this->accessService = new AccessService($em);

        $this->post = [
            'action' => 'setAccess',
            'idUser' => 6885,
            'idPages' => [2,10,11]
        ];
        $this->uid = $this->post['idUser'];
    }

    public function testGetAccessListById()
    {
        $this->assertTrue(is_array($this->accessService->getAccessIdList($this->uid)));
    }
}
