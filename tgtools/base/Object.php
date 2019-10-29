<?php
namespace app\tgtools\base;
use yii\base\UnknownPropertyException;
use yii\base\InvalidCallException;

/**
 * Description of Object
 *
 * @author GTatarnikov
 */
class Object {
    
    /**
     * Определяет имя класса.
     * @return string -- имя класса.
     */
    public static function className()
    {
        return get_called_class();
    }

    /**
     * Значение свойства, если существует.
     *
     * @param string $name -- имя свойства
     * @return mixed -- значение свойства
     * @throws UnknownPropertyException -- нет такого свойства
     * @throws InvalidCallException -- нет такого метода
     * @see __set()
     */
    public function __get($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter();
        } elseif (method_exists($this, 'set' . $name)) {
            throw new InvalidCallException('Write only property: ' . get_class($this) . '.' . $name);
        } else {
            throw new UnknownPropertyException('Get unknown property: ' . get_class($this) . '.' . $name);
        }
    }

    /**
     * Присваивает значение свойству.
     *
     * @param string $name -- имя свойства
     * @param mixed $value -- присваиваемое значение
     * @throws UnknownPropertyException -- нет такого свойства
     * @throws InvalidCallException -- нет такого метода
     * @see __get()
     */
    public function __set($name, $value)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter($value);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Read only property: ' . get_class($this) . '.' . $name);
        } else {
            throw new UnknownPropertyException('Set unknown property: ' . get_class($this) . '.' . $name);
        }
    }

    /**
     * Проверяет наличие свойства.
     *
     * @param string $name имя свойства
     * @return boolean -- флаг наличия свойства.
     */
    public function __isset($name)
    {
        $getter = 'get' . $name;
        if (method_exists($this, $getter)) {
            return $this->$getter() !== null;
        } else {
            return false;
        }
    }

    /**
     * Присваивает свойситву значение null.
     *
     * @param string $name -- имя свойства
     * @throws InvalidCallException Свойство только для чтения.
     */
    public function __unset($name)
    {
        $setter = 'set' . $name;
        if (method_exists($this, $setter)) {
            $this->$setter(null);
        } elseif (method_exists($this, 'get' . $name)) {
            throw new InvalidCallException('Read only property: ' . get_class($this) . '.' . $name);
        }
    }
}
