<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 15:42
 */

namespace Objex\Models;


use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Objex\API\Object\ObjectRepository")
 * @ORM\Table(name="object_schemas")
 **/
class ObjectSchema
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    protected $id;

    /** @ORM\Column(type="string", unique=true) **/
    protected $name;

    /** @ORM\Column(type="json") **/
    protected $data;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $token
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        if (is_null($this->data)) {
            return [];
        }

        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data = []): void
    {
        $this->data = $data;
    }



}