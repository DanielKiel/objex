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
 * @ORM\Table(name="objects")
 **/
class BaseObject
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    protected $id;

    /** @ORM\Column(type="string") **/
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
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $name
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }



}