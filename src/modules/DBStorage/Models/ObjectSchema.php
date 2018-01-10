<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 15:42
 */

namespace Objex\DBStorage\Models;


use Doctrine\ORM\Mapping as ORM;
use Objex\Core\Config\Config;

/**
 * @ORM\Entity(repositoryClass="Objex\DBStorage\Repositories\ObjectSchemaRepository")
 * @ORM\Table(name="object_schemas")
 * @ORM\HasLifecycleCallbacks
 **/
class ObjectSchema
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue **/
    protected $id;

    /** @ORM\Column(type="string", unique=true) **/
    protected $name;

    /** @ORM\Column(type="string", unique=true) **/
    protected $alias;

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
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * alias will be auto-generated
     * @param $name
     */
    protected function setAlias($name): void
    {
        if (! ctype_lower($name)) {
            //$name =str_replace('\\', '_', $name);
            $name = preg_replace('/\s+/u', '', ucwords($name));
            $name = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1-', $name));
            $name =str_replace('\\-', '_', $name);
        }

        $this->alias = $name;
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
     * @return array
     */
    public function getDefinition():array
    {
        $data = $this->getData();

        if (! array_key_exists('definition', $data)) {
            return [];
        }

        if (! is_array($data['definition'])) {
            return [];
        }

        return $data['definition'];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getConfiguration():array
    {
        $data = $this->getData();

        if (! array_key_exists('configuration', $data)) {
            return objex()->get('config')->getConfig('schema');
        }

        if (! is_array($data['configuration'])) {
            return objex()->get('config')->getConfig('schema');
        }

        return $data['configuration'];
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getValidationType(): string
    {
        $config = $this->getConfiguration();

        if (! array_key_exists('validationType', $config)) {
            return 'only';
        }

        if (! is_string($config['validationType'])) {
            return 'only';
        }

        return $config['validationType'];
    }

    /**
     * @ORM\PrePersist
     */
    public function forceAlias()
    {
        $this->setAlias($this->getName());
    }

    /**
     * @ORM\PreRemove
     * @throws \Exception
     */
    public function removeObjects()
    {
        $objects = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\BaseObject')
            ->findBy(['schema' => $this]);

        $em = objex()->get('DBStorage');
        foreach ($objects as $object) {
            $entity = $em->merge($object);
            $em->remove($entity);
        }
        $em->flush();
    }
}