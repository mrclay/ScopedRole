

CREATE TABLE `scrl_role_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_role`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_user_role` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user`    BIGINT UNSIGNED NOT NULL,
  `id_role`    BIGINT UNSIGNED NOT NULL,
  `id_context` BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_capability` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key`          VARCHAR(150) NOT NULL,
  `sortOrder`    BIGINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_context` (
  `id`     BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_contextType` BIGINT UNSIGNED,
  `key`           VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_user_capability` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user`       BIGINT UNSIGNED NOT NULL,
  `id_capability` BIGINT UNSIGNED NOT NULL,
  `id_context`    BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_role` (
  `id`    BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key`       VARCHAR(150) NOT NULL,
  `sortOrder` BIGINT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;


CREATE TABLE `scrl_contextType` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `key`           VARCHAR(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ---
-- Foreign Keys
-- ---

ALTER TABLE `scrl_role_capability` ADD FOREIGN KEY (id_role) REFERENCES `scrl_role` (`id`);
ALTER TABLE `scrl_role_capability` ADD FOREIGN KEY (id_capability) REFERENCES `scrl_capability` (`id`);
ALTER TABLE `scrl_user_role` ADD FOREIGN KEY (id_role) REFERENCES `scrl_role` (`id`);
ALTER TABLE `scrl_user_role` ADD FOREIGN KEY (id_context) REFERENCES `scrl_context` (`id`);
ALTER TABLE `scrl_context` ADD FOREIGN KEY (id_contextType) REFERENCES `scrl_contextType` (`id`);
ALTER TABLE `scrl_user_capability` ADD FOREIGN KEY (id_capability) REFERENCES `scrl_capability` (`id`);
ALTER TABLE `scrl_user_capability` ADD FOREIGN KEY (id_context) REFERENCES `scrl_context` (`id`);
