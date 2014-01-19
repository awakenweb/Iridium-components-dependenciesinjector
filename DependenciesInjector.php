<?php

/*
 * The MIT License
 *
 * Copyright (c) 2014 Mathieu SAVELLI <mathieu.savelli@awakenweb.fr>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Iridium\Components\DependenciesInjector;

/**
 * Inversion Of Control container used as a dependencies injector.
 *
 * You can use the shorter alias name DI
 *
 * @author Mathieu
 */
abstract class DependenciesInjector
{

    /**
     *
     * @var array
     */
    protected static $container = array();

    /**
     *
     * @var array
     */
    protected static $closureContainer = array();

    /**
     *
     * @var array
     */
    protected static $singletonContainer = array();

    /**
     * Add a service to the container. Two consecutive calls to DependenciesInjector::get()
     * for services declared with method will return two different instances of the same class.
     *
     * @param string   $serviceName
     * @param \Closure $service
     * 
     * @throws ServiceAlreadyDefinedException
     */
    public static function declareService( $serviceName , \Closure $serviceCallback )
    {
        if ( isset( self::$singletonContainer[ $serviceName ] ) || isset( self::$container[ $serviceName ] ) || isset( self::$closureContainer[ $serviceName ] ) ) {
            throw new Exceptions\ServiceAlreadyDefinedException( "A service with the name $serviceName is already defined" );
        }
        self::$container[ $serviceName ] = $serviceCallback;
    }

    /**
     * add a service to the container as a singleton. Two consecutive calls to
     * DependenciesInjector::get() for services declared with method will return
     * the same instance of the class.
     *
     * @param string   $serviceName
     * @param \Closure $serviceCallback
     * 
     * @throws ServiceAlreadyDefinedException
     */
    public static function declareSingleton( $serviceName , \Closure $serviceCallback )
    {
        if ( isset( self::$singletonContainer[ $serviceName ] ) || isset( self::$container[ $serviceName ] ) || isset( self::$closureContainer[ $serviceName ] ) ) {
            throw new Exceptions\ServiceAlreadyDefinedException( "A service with the name $serviceName is already defined" );
        }
        self::$singletonContainer[ $serviceName ] = $serviceCallback();
    }

    /**
     * As the declareService method cannot use closures as parameter, you should
     * use this method instead if you want to use a closure as a service.
     *
     * @param string   $serviceName
     * @param \Closure $service
     * 
     * @throws ServiceAlreadyDefinedException
     */
    public static function declareClosure( $serviceName , \Closure $service )
    {
        if ( isset( self::$singletonContainer[ $serviceName ] ) || isset( self::$container[ $serviceName ] ) || isset( self::$closureContainer[ $serviceName ] ) ) {
            throw new Exceptions\ServiceAlreadyDefinedException( "A service with the name $serviceName is already defined" );
        }
        self::$closureContainer[ $serviceName ] = $service;
    }

    /**
     * Retrieve a service from the container
     *
     * @param  string $name
     * @return mixed
     *
     * @throws NoServiceException
     */
    public static function get( $name )
    {
        if ( isset( self::$container[ $name ] ) ) {
            return call_user_func( self::$container[ $name ] );
        } elseif ( isset( self::$singletonContainer[ $name ] ) ) {
            return self::$singletonContainer[ $name ];
        } elseif ( isset( self::$closureContainer[ $name ] ) ) {
            return self::$closureContainer[ $name ];
        } else {
            throw new Exceptions\NoServiceException( "Service $name does not exist" );
        }
    }

    /**
     * Syntaxic sugar for the DI::get function.
     *
     * Example: DependenciesInjector::get('service'); is equivalent to DependenciesInjector::service();
     *
     * @param string $name
     * @param array  $arguments
     */
    public static function __callStatic( $name , $arguments )
    {
        return self::get( $name );
    }

}
