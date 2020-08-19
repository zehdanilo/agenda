CREATE DATABASE agenda CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE DATABASE agenda_admin CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE DATABASE agenda_auditoria CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE USER 'user'@'%' IDENTIFIED BY '1q2w3e';
GRANT SELECT, INSERT, UPDATE, DELETE ON agenda.* TO 'user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON agenda_admin.* TO 'user'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON agenda_auditoria.* TO 'user'@'%';

FLUSH PRIVILEGES;