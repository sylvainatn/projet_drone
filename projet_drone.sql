-- SGBD PostgreSQL utilisé pour le projet

-- Création du schéma "drone"
CREATE SCHEMA drone
    AUTHORIZATION postgres;


-- Création de la table utilisateur
CREATE TABLE drone.utilisateur (
  	id SERIAL NOT NULL PRIMARY KEY,
  	login varchar(50) DEFAULT NULL,
  	mdp varchar(50) DEFAULT NULL
);


-- Création de table parcours
CREATE TABLE drone.parcours (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(255) UNIQUE NOT NULL
);


-- Création de la table coordonnee
CREATE TABLE drone.coordonnee (
    id SERIAL PRIMARY KEY,
    parcours_id INT REFERENCES drone.parcours(id) ON DELETE CASCADE,
	point VARCHAR(10) DEFAULT NULL,
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL
);


-- Création de la table position
CREATE TABLE drone.position (
  	id_position SERIAL NOT NULL PRIMARY KEY,
  	latitude varchar(11) DEFAULT NULL,
  	longitude varchar(11) DEFAULT NULL,
  	altitude varchar(11) DEFAULT NULL,
  	timestamp timestamp NULL DEFAULT NULL
);


-- Création de la table mesure
CREATE TABLE drone.mesure (
  id_mesure SERIAL NOT NULL PRIMARY KEY,
  distance_objet varchar(11) DEFAULT NULL,
  timestamp timestamp NULL DEFAULT NULL
);


-- Création de la table de jointure position_mesure
CREATE TABLE drone.position_mesure (
  	id_pos_mesure SERIAL NOT NULL PRIMARY KEY,
  	POSITIONid_position INT4 REFERENCES drone.position(id_position),
  	MESUREid_mesure INT4 REFERENCES drone.mesure(id_mesure)
);


-- Insertion du login et mdp dans la table utilisateur
INSERT INTO drone.utilisateur (id, login, mdp) VALUES 
(1, 'drone', md5('drone'));


-- Insertion des coordonnées GPS du drone dans la table position
INSERT INTO drone.position (id_position, latitude, longitude) VALUES 
(1, '48.88297862', '2.612355245');