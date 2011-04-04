
-- ---
-- Table 'roles_capabilities'
--
-- ---

CREATE TABLE `%PREFIX%roles_capabilities` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `roleId`       BIGINT UNSIGNED NOT NULL,
  `capabilityId` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'users_roles'
--
-- ---

CREATE TABLE `%PREFIX%users_roles` (
  `id`        BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `userId`    BIGINT UNSIGNED NOT NULL,
  `roleId`    BIGINT UNSIGNED NOT NULL,
  `contextId` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'capabilities'
--
-- ---

CREATE TABLE `%PREFIX%capabilities` (
  `capabilityId` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`          VARCHAR NOT NULL,
  `sortOrder`    BIGINT DEFAULT NULL,
  PRIMARY KEY (`capabilityId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'contexts'
--
-- ---

CREATE TABLE `%PREFIX%contexts` (
  `contextId`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `contextTypeId` BIGINT UNSIGNED DEFAULT NULL,
  `key`           VARCHAR NOT NULL,
  PRIMARY KEY (`contextId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'users_capabilities'
-- enable special cases per-user
-- ---

CREATE TABLE `%PREFIX%users_capabilities` (
  `id`           BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `userId`       BIGINT UNSIGNED NOT NULL,
  `capabilityId` BIGINT UNSIGNED NOT NULL,
  `contextId`    BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) COMMENT='enable special cases per-user'
 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;;

-- ---
-- Table 'roles'
--
-- ---

CREATE TABLE `%PREFIX%roles` (
  `roleId`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`       VARCHAR NOT NULL,
  `sortOrder` BIGINT DEFAULT NULL,
  PRIMARY KEY (`roleId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'contextTypes'
--
-- ---

CREATE TABLE `%PREFIX%contextTypes` (
  `contextTypeId` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`           VARCHAR NOT NULL,
  PRIMARY KEY (`contextTypeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `%PREFIX%roles_capabilities` ADD FOREIGN KEY (roleId) REFERENCES `%PREFIX%roles` (`roleId`);
ALTER TABLE `%PREFIX%roles_capabilities` ADD FOREIGN KEY (capabilityId) REFERENCES `%PREFIX%capabilities` (`capabilityId`);
ALTER TABLE `%PREFIX%users_roles` ADD FOREIGN KEY (roleId) REFERENCES `%PREFIX%roles` (`roleId`);
ALTER TABLE `%PREFIX%users_roles` ADD FOREIGN KEY (contextId) REFERENCES `%PREFIX%contexts` (`contextId`);
ALTER TABLE `%PREFIX%contexts` ADD FOREIGN KEY (contextTypeId) REFERENCES `%PREFIX%contextTypes` (`contextTypeId`);
ALTER TABLE `%PREFIX%users_capabilities` ADD FOREIGN KEY (capabilityId) REFERENCES `%PREFIX%capabilities` (`capabilityId`);
ALTER TABLE `%PREFIX%users_capabilities` ADD FOREIGN KEY (contextId) REFERENCES `%PREFIX%contexts` (`contextId`);
