ALTER TABLE `scrl_role_capability` ADD FOREIGN KEY (id_role) REFERENCES `scrl_role` (`id`);
/* separator */
ALTER TABLE `scrl_role_capability` ADD FOREIGN KEY (id_capability) REFERENCES `scrl_capability` (`id`);
/* separator */
ALTER TABLE `scrl_user_role` ADD FOREIGN KEY (id_role) REFERENCES `scrl_role` (`id`);
/* separator */
ALTER TABLE `scrl_user_role` ADD FOREIGN KEY (id_context) REFERENCES `scrl_context` (`id`);
/* separator */
ALTER TABLE `scrl_context` ADD FOREIGN KEY (id_contextType) REFERENCES `scrl_contextType` (`id`);
/* separator */
ALTER TABLE `scrl_user_capability` ADD FOREIGN KEY (id_capability) REFERENCES `scrl_capability` (`id`);
/* separator */
ALTER TABLE `scrl_user_capability` ADD FOREIGN KEY (id_context) REFERENCES `scrl_context` (`id`);
