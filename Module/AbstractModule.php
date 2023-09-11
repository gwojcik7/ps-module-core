<?php

/**
 * NOTICE OF LICENSE
 *
 * This file is licensed under the Software License Agreement.
 *
 * With the purchase or the installation of the software in your application
 * you accept the license agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 * @author Arkonsoft
 * @copyright 2023 Arkonsoft
 */

declare(strict_types=1);

namespace Arkonsoft\PrestaShop\Core\Module;

use Db;
use DbQuery;
use Module;

if (!defined('_PS_VERSION_')) {
    exit;
}

class AbstractModule extends Module
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return string|null
     */
    protected function getDatabaseVersion()
    {
        $query = new DbQuery();
        $query->select('version');
        $query->from('module');
        $query->where('name = "' . $this->name . '"');

        $result = Db::getInstance()->getValue($query);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    protected function canBeUpgraded(): bool
    {
        $dbVersion = $this->getDatabaseVersion();

        if (empty($dbVersion) || empty($this->version)) {
            return false;
        }

        return version_compare($dbVersion, (string) $this->version, '<');
    }
}
