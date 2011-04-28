
-- ---
-- Table 'role_capabilitie'
--
-- ---

CREATE TABLE `%PREFIX%role_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `id_role`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'users_role'
--
-- ---

CREATE TABLE `%PREFIX%user_role` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `id_user`    BIGINT UNSIGNED NOT NULL,
  `id_role`    BIGINT UNSIGNED NOT NULL,
  `id_context` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'capabilitie'
--
-- ---

CREATE TABLE `%PREFIX%capability` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`          VARCHAR NOT NULL,
  `sortOrder`    BIGINT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'context'
--
-- ---

CREATE TABLE `%PREFIX%context` (
  `id`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `id_contextType` BIGINT UNSIGNED DEFAULT NULL,
  `key`           VARCHAR NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'user_capabilitie'
-- enable special cases per-user
-- ---

CREATE TABLE `%PREFIX%user_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `id_user`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL,
  `id_context`    BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) COMMENT='enable special cases per-user'
 ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;;

-- ---
-- Table 'role'
--
-- ---

CREATE TABLE `%PREFIX%role` (
  `id`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`       VARCHAR NOT NULL,
  `sortOrder` BIGINT DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Table 'contextType'
--
-- ---

CREATE TABLE `%PREFIX%contextType` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE,
  `key`           VARCHAR NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `%PREFIX%role_capability` ADD FOREIGN KEY (id_role) REFERENCES `%PREFIX%role` (`id`);
ALTER TABLE `%PREFIX%role_capability` ADD FOREIGN KEY (id_capability) REFERENCES `%PREFIX%capability` (`id`);
ALTER TABLE `%PREFIX%user_role` ADD FOREIGN KEY (id_role) REFERENCES `%PREFIX%role` (`id`);
ALTER TABLE `%PREFIX%user_role` ADD FOREIGN KEY (id_context) REFERENCES `%PREFIX%context` (`id`);
ALTER TABLE `%PREFIX%context` ADD FOREIGN KEY (id_contextType) REFERENCES `%PREFIX%contextType` (`id`);
ALTER TABLE `%PREFIX%user_capability` ADD FOREIGN KEY (id_capability) REFERENCES `%PREFIX%capability` (`id`);
ALTER TABLE `%PREFIX%user_capability` ADD FOREIGN KEY (id_context) REFERENCES `%PREFIX%context` (`id`);
