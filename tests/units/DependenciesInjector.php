<?php

namespace Iridium\Components\DependenciesInjector\tests\units;

require_once __DIR__ . '/../../vendor/autoload.php';

use atoum ,
    Iridium\Components\DependenciesInjector\DI;

/**
 * Description of DependenciesInjector
 *
 * @author Mathieu
 */
class DependenciesInjector extends atoum
{

    public function testDeclareServiceAndGet()
    {
        DI::declareService( 'testService' , function () {
            return new \stdClass();
        } );

        $serv  = DI::get( 'testService' );
        $serv2 = DI::get( 'testService' );

        $this->object( $serv )
                ->isInstanceOf( '\stdClass' )
                ->object( $serv2 )
                ->isInstanceOf( '\stdClass' )
                ->isNotIdenticalTo( $serv );

        $this->exception( function () {
                    DI::declareService( 'testService' , function () {
                        
                    } );
                } )
                ->isInstanceOf( '\Iridium\Components\DependenciesInjector\Exceptions\ServiceAlreadyDefinedException' );
    }

    public function testDeclareClosure()
    {
        DI::declareClosure( 'testService' , function () {
            return 'hello world';
        } );

        $closure = DI::get( 'testService' );

        $this->variable( $closure )
                ->isCallable()
                ->string( $closure() )
                ->isEqualTo('hello world');

        $this->exception( function () {
                    DI::declareClosure( 'testService' , function () {
                        
                    } );
                } )
                ->isInstanceOf( '\Iridium\Components\DependenciesInjector\Exceptions\ServiceAlreadyDefinedException' );
    }

    public function testSingletonDeclarationAndGet()
    {
        DI::declareSingleton( 'testService' , function () {
            return new \stdClass();
        } );

        $serv  = DI::get( 'testService' );
        $serv2 = DI::get( 'testService' );

        $this->object( $serv )
                ->isInstanceOf( '\stdClass' )
                ->object( $serv2 )
                ->isInstanceOf( '\stdClass' )
                ->isIdenticalTo( $serv );

        $this->exception( function () {
                    DI::declareSingleton( 'testService' , function () {
                        
                    } );
                } )
                ->isInstanceOf( '\Iridium\Components\DependenciesInjector\Exceptions\ServiceAlreadyDefinedException' );
    }

    public function testGetThrowsException()
    {
        $this->exception( function () {
                    $serv = DI::get( 'testService' );
                } )
                ->isInstanceOf( '\Iridium\Components\DependenciesInjector\Exceptions\NoServiceException' );
    }

    public function testSyntaxicSugar()
    {
        DI::declareService( 'testService' , function () {
            return new \stdClass();
        } );

        DI::declareSingleton( 'testSingleton' , function () {
            return new \stdClass();
        } );

        $obj = DI::get( 'testService' );

        $single = DI::get( 'testSingleton' );

        $this->object( DI::testService() )
                ->isInstanceOf( '\stdClass' )
                ->isNotIdenticalTo( $obj );

        $this->object( DI::testSingleton() )
                ->isInstanceOf( '\stdClass' )
                ->isIdenticalTo( $single );
    }

}
