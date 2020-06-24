<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\DoctrineMigrationWrapper;

use OxidEsales\EshopCommunity\Internal\Container\BootstrapContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Module\Configuration\Dao\ProjectConfigurationDaoInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Utility\BasicContextInterface;
use OxidEsales\Facts\Facts;
use Webmozart\PathUtil\Path;

class MigrationsPathProvider implements MigrationsPathProviderInterface
{
    /**
     * @var Facts
     */
    private $facts;

    /**
     * @param Facts $facts
     */
    public function __construct(Facts $facts)
    {
        $this->facts = $facts;
    }

    /**
     * @param null $edition
     *
     * @return array
     */
    public function getMigrationsPath($edition = null): array
    {
        $allMigrationPaths = array_merge($this->getShopEditionsPath(), $this->getModulesPath());

        if (is_null($edition)) {
            return $allMigrationPaths;
        }

        $migrationPaths = [];
        foreach ($allMigrationPaths as $migrationEdition => $migrationPath) {
            if (strtolower($migrationEdition) === strtolower($edition)) {
                $migrationPaths[$migrationEdition] = $migrationPath;
                break;
            }
        }

        return $migrationPaths;
    }

    /**
     * @return array
     */
    private function getShopEditionsPath(): array
    {
        $migrationPaths = [
            'ce' => Path::join($this->facts->getSourcePath(), 'migration', 'migrations.yml')
        ];

        if ($this->facts->isProfessional() || $this->facts->isEnterprise()) {
            $migrationPaths['pe'] = Path::join(
                $this->facts->getProfessionalEditionRootPath(),
                'migration', 'migrations.yml'
            );
        }

        if ($this->facts->isEnterprise()) {
            $migrationPaths['ee'] = Path::join(
                $this->facts->getEnterpriseEditionRootPath(),
                'migration', 'migrations.yml'
            );
        }

        $migrationPaths['pr'] = Path::join($this->facts->getSourcePath()
            , 'migration', 'project_migrations.yml');

        return $migrationPaths;
    }

    /**
     * @return array
     */
    private function getModulesPath(): array
    {
        $moduleMigrationPaths = [];

        $bootstrapContainer = BootstrapContainerFactory::getBootstrapContainer();

        $projectConfigurationDao = $bootstrapContainer
            ->get(ProjectConfigurationDaoInterface::class);

        $basicContext = $bootstrapContainer
            ->get(BasicContextInterface::class);

        $shopConfigurationDao = $projectConfigurationDao
            ->getConfiguration()
            ->getShopConfiguration($basicContext->getDefaultShopId());

        foreach ($shopConfigurationDao->getModuleConfigurations() as $moduleConfiguration) {
            $migrationConfigurationPath = Path::join(
                $basicContext->getModuleSourcePath(),
                '/migration/migrations.yml'
            );
            if (file_exists($migrationConfigurationPath)) {
                $moduleMigrationPaths[$moduleConfiguration->getId()] = $migrationConfigurationPath;
            }
        }

        return $moduleMigrationPaths;
    }
}
