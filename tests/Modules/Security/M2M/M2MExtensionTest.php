<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:05
 */


class M2MExtensionTest extends \PHPUnit\Framework\TestCase
{
    public function testMachineProvider()
    {
        $repo = objex()->get('DBStorage')
            ->getRepository('Objex\Security\Models\Machine');

        $name = 'MyMachine_' . uniqid();

        $machine = $repo->save($name);

        $this->assertInstanceOf(\Objex\Security\Models\Machine::class, $machine);
        $this->assertEquals($name, $machine->getUsername());

        $foundByApiKey = $repo->findOneBy([
            'apiKey' => $machine->getApiKey()
        ]);

        $this->assertEquals($machine->getUsername(), $foundByApiKey->getUsername());

        objex()->get('DBStorage')->remove($machine);
        objex()->get('DBStorage')->flush();

        $foundByApiKey = $repo->findOneBy([
            'apiKey' => $machine->getApiKey()
        ]);

        $this->assertEmpty($foundByApiKey);
    }
}
