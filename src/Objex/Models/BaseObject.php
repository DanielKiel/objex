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
    protected $token;

    /** @ORM\Column(type="json") **/
    protected $data;

    /**
     * @ORM\ManyToOne(targetEntity="Objex\Models\ObjectSchema")
     * @ORM\JoinColumn(name="schema_id", referencedColumnName="id", nullable=false)
     *
     */
    protected $schema;

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
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
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

    /**
     * @return ObjectSchema
     */
    public function getSchema(): ObjectSchema
    {
        return $this->schema;
    }

    /**
     * @param ObjectSchema $schema
     */
    public function setSchema(ObjectSchema $schema)
    {
        $this->schema = $schema;
    }

}