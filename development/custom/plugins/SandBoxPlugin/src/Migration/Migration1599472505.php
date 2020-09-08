<?php declare(strict_types=1);

namespace SandBoxPlugin\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1599472505 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1599472505;
    }

    public function update(Connection $connection): void
    {
        $connection->exec("CREATE TABLE IF NOT EXISTS `sand_box_plugin`
(
    `id`         BINARY(16)   NOT NULL,
    `active`     TINYINT(1)   NULL DEFAULT '0',
    `name`       VARCHAR(255) NOT NULL,
    `street`     VARCHAR(255) NOT NULL,
    `post_code`  VARCHAR(255) NOT NULL,
    `city`       VARCHAR(255) NOT NULL,
    `url`        VARCHAR(255) NOT NULL,
    `telephone`  VARCHAR(255) NOT NULL,
    `open_times` LONGTEXT     NULL,
    `country_id` BINARY(16)   NULL,
    `created_at` DATETIME(3),
    `updated_at` DATETIME(3),
    primary key (`id`),
    KEY `fk.sand_box_plugin.country.id` (`country_id`),
    CONSTRAINT `fk.sand_box_plugin.country.id` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB
  default CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}