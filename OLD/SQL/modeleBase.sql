DROP TABLE IF EXISTS User ;
CREATE TABLE User (idUser INT  AUTO_INCREMENT NOT NULL,
pseudoUser VARCHAR(50),
motDePasse VARCHAR(50),
pathAvatar VARCHAR(50),
enableNotif TINYINT,
seuilCredibMin INT,
seuilDistanceMax INT,
PRIMARY KEY (idUser) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Credibilite ;
CREATE TABLE Credibilite (idCredibilite INT  AUTO_INCREMENT NOT NULL,
libelleCredibilite VARCHAR(20),
idEtatCredibilite INT,
valeurCredibilite INT,
PRIMARY KEY (idCredibilite) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Distance ;
CREATE TABLE Distance (idDistance INT  AUTO_INCREMENT NOT NULL,
valeurDistance INT,
PRIMARY KEY (idDistance) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Commentaire ;
CREATE TABLE Commentaire (idCommentaire INT  AUTO_INCREMENT NOT NULL,
description TEXT,
dateCommentaire DATETIME,
idUser INT NOT NULL,
idIncident INT NOT NULL,
PRIMARY KEY (idCommentaire) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Incident ;
CREATE TABLE Incident (idIncident INT  AUTO_INCREMENT NOT NULL,
descriptionIncident VARCHAR(30),
lattitudeIncident VARCHAR(30),
longitudeIncident VARCHAR(30),
incidentEnCours TINYINT,
idCredibilite INT NOT NULL,
idType INT NOT NULL,
PRIMARY KEY (idIncident) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Type_incident ;
CREATE TABLE Type_incident (idType INT  AUTO_INCREMENT NOT NULL,
nomType VARCHAR(30),
PRIMARY KEY (idType) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Affiche ;
CREATE TABLE Affiche (idUser INT  AUTO_INCREMENT NOT NULL,
idDistance INT NOT NULL,
PRIMARY KEY (idUser,
 idDistance) ) ENGINE=InnoDB;

DROP TABLE IF EXISTS Signale ;
CREATE TABLE Signale (idUser INT  AUTO_INCREMENT NOT NULL,
idIncident INT NOT NULL,
idDeclaration INT,
dateDeclaration DATETIME,
PRIMARY KEY (idUser,
 idIncident) ) ENGINE=InnoDB;

ALTER TABLE Commentaire ADD CONSTRAINT FK_Commentaire_idUser FOREIGN KEY (idUser) REFERENCES User (idUser);

ALTER TABLE Commentaire ADD CONSTRAINT FK_Commentaire_idIncident FOREIGN KEY (idIncident) REFERENCES Incident (idIncident);
ALTER TABLE Incident ADD CONSTRAINT FK_Incident_idCredibilite FOREIGN KEY (idCredibilite) REFERENCES Credibilite (idCredibilite);
ALTER TABLE Incident ADD CONSTRAINT FK_Incident_idType FOREIGN KEY (idType) REFERENCES Type_incident (idType);
ALTER TABLE Affiche ADD CONSTRAINT FK_Affiche_idUser FOREIGN KEY (idUser) REFERENCES User (idUser);
ALTER TABLE Affiche ADD CONSTRAINT FK_Affiche_idDistance FOREIGN KEY (idDistance) REFERENCES Distance (idDistance);
ALTER TABLE Signale ADD CONSTRAINT FK_Signale_idUser FOREIGN KEY (idUser) REFERENCES User (idUser);
ALTER TABLE Signale ADD CONSTRAINT FK_Signale_idIncident FOREIGN KEY (idIncident) REFERENCES Incident (idIncident);
