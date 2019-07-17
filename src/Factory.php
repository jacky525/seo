<?php
namespace Melbahja\Seo;

use Melbahja\Seo\Interfaces\SeoInterface;

/**
 * @package Melbahja\Seo
 * @since v1.0
 * @see https://git.io/phpseo 
 * @license MIT
 * @copyright 2019 Mohamed Elabhja 
 */
class Factory
{
	/**
	 * Build a SEO object
	 *
	 * @param  string $class
	 * @param  array  $args
	 * @return SeoInterface
	 */

    private static $instance = NULL;

	public static function __callStatic( $class,  $args)
	{
		if (class_exists($class = __NAMESPACE__ . '\\' . ucfirst($class))) {

            $reflection = new \ReflectionClass($class);
//            $arguments = array_shift($args);
            self::$instance = $reflection->newInstanceArgs($args);
//            call_user_func_array(
//                array($reflection, 'Factor'),
//                $arguments
//            );

            return self::$instance;


//            $reflect = new $class($args[0]);
//            $arguments = array_shift($args);
//            // TODO check if $functionName exists, otherwise we will get a loop
//            call_user_func_array(
//                array($reflect, $class),
//                $arguments
//            );
//            return $reflect;

//			return new $class(...$args);
		}

		throw new Exceptions\SeoException("The class {$class} is not exists");
	}
}
