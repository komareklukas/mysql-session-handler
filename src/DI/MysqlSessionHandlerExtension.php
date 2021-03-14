<?php

namespace Pematon\Session\DI;

use Nette;

class MysqlSessionHandlerExtension extends Nette\DI\CompilerExtension
{
	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();

        $definition = $builder->addDefinition($this->prefix('sessionHandler'))
            ->setType('Pematon\Session\MysqlSessionHandler')
            ->setArguments([$this->getContainerBuilder()->getDefinition('database.iam.context') ])
            ->addSetup('setTableName', ['session']);
        
		$sessionDefinition = $builder->getDefinition('session');
		$sessionSetup = $sessionDefinition->getSetup();
		# Prepend setHandler method to other possible setups (setExpiration) which would start session prematurely
		array_unshift($sessionSetup, new Nette\DI\Statement('setHandler', array($definition)));
		$sessionDefinition->setSetup($sessionSetup);
	}
}
