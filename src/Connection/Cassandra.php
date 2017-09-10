<?php
/*
 * Fusio
 * A web-application to create dynamically RESTful APIs
 *
 * Copyright (C) 2015-2017 Christoph Kappestein <christoph.kappestein@gmail.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Fusio\Adapter\Cassandra\Connection;

use Cassandra\SimpleStatement;
use Fusio\Engine\Connection\PingableInterface;
use Fusio\Engine\ConnectionInterface;
use Fusio\Engine\Exception\ConfigurationException;
use Fusio\Engine\Form\BuilderInterface;
use Fusio\Engine\Form\ElementFactoryInterface;
use Fusio\Engine\ParametersInterface;

/**
 * Cassandra
 *
 * @author  Christoph Kappestein <christoph.kappestein@gmail.com>
 * @license http://www.gnu.org/licenses/agpl-3.0
 * @link    http://fusio-project.org
 */
class Cassandra implements ConnectionInterface, PingableInterface
{
    public function getName()
    {
        return 'Cassandra';
    }

    /**
     * @param \Fusio\Engine\ParametersInterface $config
     * @return \Cassandra\Session
     */
    public function getConnection(ParametersInterface $config)
    {
        if (class_exists('Cassandra')) {
            /** @var \Cassandra\Cluster\Builder $builder */
            $builder = \Cassandra::cluster();

            $host = $config->get('host');
            if (!empty($host)) {
                $hosts = explode(',', $host);
                $builder->withContactPoints(...$hosts);
            }

            $port = $config->get('port');
            if (!empty($port)) {
                $builder->withPort($port);
            }

            $cluster  = $builder->build();
            $keyspace = $config->get('keyspace');

            if (!empty($keyspace)) {
                return $cluster->connect($keyspace);
            } else {
                return $cluster->connect();
            }
        } else {
            throw new ConfigurationException('PHP extension "cassandra" is not installed');
        }
    }

    public function configure(BuilderInterface $builder, ElementFactoryInterface $elementFactory)
    {
        $builder->add($elementFactory->newInput('host', 'Host', 'text', 'Configures the initial endpoints. Note that the driver will automatically discover and connect to the rest of the cluster'));
        $builder->add($elementFactory->newInput('port', 'Port', 'number', 'Specify a different port to be used when connecting to the cluster'));
        $builder->add($elementFactory->newInput('keyspace', 'Keyspace', 'text', 'Optional keyspace name'));
    }

    public function ping($connection)
    {
        if ($connection instanceof \Cassandra\Session) {
            try {
                $connection->execute(new SimpleStatement('SELECT release_version FROM system.local'));

                return true;
            } catch (\Cassandra\Exception $e) {
            }
        }

        return false;
    }
}
