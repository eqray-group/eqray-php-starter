<?php
// config/services.php
// 这个是个核心的配置文件，如果不懂，请参考symfony服务注册器的语法或下面的例子

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;


return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    // 默认配置
    $services
        ->defaults()
        ->autowire()      // 所有服务默认自动装配
        ->autoconfigure() // 所有服务默认自动配置
		->public();
		
	#(new SessionServiceProvider())($configurator);
    //$parameters = $configurator->parameters();
    //$parameters->set('database.engine', env('ORM_DRIVER')?? 'thinkORM'); // 可以在 .env 中被覆盖	

    $services->set('test', \stdClass::class)->public();



	//$services->load('App\\Dao\\', '../app/Dao/');

	$services->load('App\\Dao\\', BASE_PATH. '/app/Dao/**/*.php')
		->autowire()
		->autoconfigure()
		->public();	
	
	// Eloquent BaseModel alias
	class_alias(
		\Framework\Basic\BaseLaORMModel::class,
		\Framework\Utils\BaseModel::class,
		true
	);
	

    // ✅ 1. 自动加载应用 Provider
    $providerManager = new \Framework\Container\ContainerProviders();

	// ✅ 2. 自动加载核心 + 应用 Provider
	$providerManager->loadAll(
		$configurator,
		'App\\Providers\\',
		BASE_PATH . '/app/Providers'
		
	);


    // ✅ 3. 启动所有 Provider（boot）
	\Framework\Container\Container::setProviderManager($providerManager);

	
	

};
