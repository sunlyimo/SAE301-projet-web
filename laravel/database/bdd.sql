CREATE DATABASE IF NOT EXISTS MLR2;
USE MLR2;
# -----------------------------------------------------------------------------
#       TABLE : VIK_COUREUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COUREUR
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   CLU_ID INTEGER(4) NULL  ,
   CRR_PPS CHAR(32) NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_COUREUR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_COUREUR_VIK_CLUB
     ON VIK_COUREUR (CLU_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_COURSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COURSE
 (
   RAI_ID INTEGER(4) NOT NULL  ,
   COU_ID INTEGER(2) NOT NULL  ,
   TYP_ID INTEGER(2) NOT NULL  ,
   DIF_NIVEAU INTEGER(2) NOT NULL  ,
   UTI_ID INTEGER(2) NOT NULL  ,
   COU_NOM CHAR(50) NULL  ,
   COU_DATE_DEBUT DATETIME NULL  ,
   COU_DATE_FIN DATETIME NULL  ,
   COU_PRIX DECIMAL(13,2) NULL  ,
   COU_PRIX_ENFANT DECIMAL(13,2) NULL  ,
   COU_PARTICIPANT_MIN INTEGER(3) NULL  ,
   COU_PARTICIPANT_MAX INTEGER(3) NULL  ,
   COU_EQUIPE_MIN INTEGER(3) NULL  ,
   COU_EQUIPE_MAX INTEGER(3) NULL  ,
   COU_PARTICIPANT_PAR_EQUIPE_MAX INTEGER(2) NULL  ,
   COU_REPAS_PRIX DECIMAL(13,2) NULL  ,
   COU_REDUCTION DECIMAL(13,2) NULL  ,
   COU_LIEU CHAR(100) NULL  ,
   COU_AGE_MIN INTEGER(3) NULL  ,
   COU_AGE_SEUL INTEGER(3) NULL  ,
   COU_AGE_ACCOMPAGNATEUR INTEGER(3) NULL  
   , PRIMARY KEY (RAI_ID,COU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_COURSE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_COURSE_VIK_COURSE_TYPE
     ON VIK_COURSE (TYP_ID ASC);

CREATE  INDEX I_FK_VIK_COURSE_VIK_RESPONSABLE_COURSE
     ON VIK_COURSE (UTI_ID ASC);

CREATE  INDEX I_FK_VIK_COURSE_VIK_RAID
     ON VIK_COURSE (RAI_ID ASC);

CREATE  INDEX I_FK_VIK_COURSE_VIK_TRANCHE_DIFFICULTE
     ON VIK_COURSE (DIF_NIVEAU ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_TRANCHE_DIFFICULTE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_TRANCHE_DIFFICULTE
 (
   DIF_NIVEAU INTEGER(2) NOT NULL  ,
   DIF_DESCRIPTION CHAR(100) NULL  
   , PRIMARY KEY (DIF_NIVEAU) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_ADMINISTRATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_ADMINISTRATEUR
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_RESPONSABLE_RAID
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RESPONSABLE_RAID
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_UTILISATEUR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_UTILISATEUR
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_EQUIPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_EQUIPE
 (
   RAI_ID INTEGER(4) NOT NULL  ,
   COU_ID INTEGER(2) NOT NULL  ,
   EQU_ID INTEGER(2) NOT NULL  ,
   UTI_ID INTEGER(2) NOT NULL  
   , PRIMARY KEY (RAI_ID,COU_ID,EQU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_EQUIPE
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_EQUIPE_VIK_COURSE
     ON VIK_EQUIPE (RAI_ID ASC,COU_ID ASC);

CREATE  INDEX I_FK_VIK_EQUIPE_VIK_UTILISATEUR
     ON VIK_EQUIPE (UTI_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_COURSE_TYPE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_COURSE_TYPE
 (
   TYP_ID INTEGER(2) NOT NULL  ,
   TYP_DESCRIPTION CHAR(100) NULL  
   , PRIMARY KEY (TYP_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_CLUB
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_CLUB
 (
   CLU_ID INTEGER(4) NOT NULL  ,
   CLU_NOM CHAR(50) NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL
   , PRIMARY KEY (CLU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_RESULTAT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RESULTAT
 (
   RAI_ID INTEGER(4) NOT NULL  ,
   COU_ID INTEGER(2) NOT NULL  ,
   EQU_ID INTEGER(2) NOT NULL  ,
   RES_RANG CHAR(32) NULL  ,
   RES_TEMPS CHAR(32) NULL  ,
   RES_POINT INTEGER(4) NULL  
   , PRIMARY KEY (RAI_ID,COU_ID,EQU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_RAID
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RAID
 (
   RAI_ID INTEGER(4) NOT NULL  ,
   CLU_ID INTEGER(4) NOT NULL  ,
   UTI_ID INTEGER(2) NOT NULL  ,
   RAI_NOM CHAR(50) NULL  ,
   RAI_RAID_DATE_DEBUT DATETIME NULL  ,
   RAI_RAID_DATE_FIN DATETIME NULL  ,
   RAI_INSCRI_DATE_DEBUT DATETIME NULL  ,
   RAI_INSCRI_DATE_FIN DATETIME NULL  ,
   RAI_CONTACT CHAR(50) NULL  ,
   RAI_WEB CHAR(50) NULL  ,
   RAI_LIEU CHAR(50) NULL  ,
   RAI_IMAGE CHAR(50) NULL  
   , PRIMARY KEY (RAI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_RAID
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_RAID_VIK_RESPONSABLE_RAID
     ON VIK_RAID (UTI_ID ASC);

CREATE  INDEX I_FK_VIK_RAID_VIK_CLUB
     ON VIK_RAID (CLU_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_RESPONSABLE_COURSE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RESPONSABLE_COURSE
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       TABLE : VIK_RESPONSABLE_CLUB
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_RESPONSABLE_CLUB
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   CLU_ID INTEGER(4) NOT NULL  ,
   UTI_EMAIL CHAR(32) NULL  ,
   UTI_NOM CHAR(50) NULL  ,
   UTI_PRENOM CHAR(50) NULL  ,
   UTI_DATE_NAISSANCE DATE NULL  ,
   UTI_RUE CHAR(50) NULL  ,
   UTI_CODE_POSTAL CHAR(10) NULL  ,
   UTI_VILLE CHAR(50) NULL  ,
   UTI_TELEPHONE CHAR(16) NULL  ,
   UTI_LICENCE CHAR(15) NULL  ,
   UTI_NOM_UTILISATEUR CHAR(255) NOT NULL  ,
   UTI_MOT_DE_PASSE CHAR(32) NULL  
   , PRIMARY KEY (UTI_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_RESPONSABLE_CLUB
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_RESPONSABLE_CLUB_VIK_CLUB
     ON VIK_RESPONSABLE_CLUB (CLU_ID ASC);

# -----------------------------------------------------------------------------
#       TABLE : VIK_APPARTIENT
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS VIK_APPARTIENT
 (
   UTI_ID INTEGER(2) NOT NULL  ,
   RAI_ID INTEGER(4) NOT NULL  ,
   COU_ID INTEGER(2) NOT NULL  ,
   EQU_ID INTEGER(2) NOT NULL  
   , PRIMARY KEY (UTI_ID,RAI_ID,COU_ID,EQU_ID) 
 ) 
 comment = "";

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE VIK_APPARTIENT
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_VIK_APPARTIENT_VIK_COUREUR
     ON VIK_APPARTIENT (UTI_ID ASC);

CREATE  INDEX I_FK_VIK_APPARTIENT_VIK_EQUIPE
     ON VIK_APPARTIENT (RAI_ID ASC,COU_ID ASC,EQU_ID ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE VIK_COUREUR 
  ADD FOREIGN KEY FK_VIK_COUREUR_VIK_CLUB (CLU_ID)
      REFERENCES VIK_CLUB (CLU_ID) ;


ALTER TABLE VIK_COUREUR 
  ADD FOREIGN KEY FK_VIK_COUREUR_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_COURSE_TYPE (TYP_ID)
      REFERENCES VIK_COURSE_TYPE (TYP_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_RESPONSABLE_COURSE (UTI_ID)
      REFERENCES VIK_RESPONSABLE_COURSE (UTI_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_RAID (RAI_ID)
      REFERENCES VIK_RAID (RAI_ID) ;


ALTER TABLE VIK_COURSE 
  ADD FOREIGN KEY FK_VIK_COURSE_VIK_TRANCHE_DIFFICULTE (DIF_NIVEAU)
      REFERENCES VIK_TRANCHE_DIFFICULTE (DIF_NIVEAU) ;


ALTER TABLE VIK_ADMINISTRATEUR 
  ADD FOREIGN KEY FK_VIK_ADMINISTRATEUR_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_RESPONSABLE_RAID 
  ADD FOREIGN KEY FK_VIK_RESPONSABLE_RAID_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_EQUIPE 
  ADD FOREIGN KEY FK_VIK_EQUIPE_VIK_COURSE (RAI_ID,COU_ID)
      REFERENCES VIK_COURSE (RAI_ID,COU_ID) ;


ALTER TABLE VIK_EQUIPE 
  ADD FOREIGN KEY FK_VIK_EQUIPE_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_RESULTAT 
  ADD FOREIGN KEY FK_VIK_RESULTAT_VIK_EQUIPE (RAI_ID,COU_ID,EQU_ID)
      REFERENCES VIK_EQUIPE (RAI_ID,COU_ID,EQU_ID) ;


ALTER TABLE VIK_RAID 
  ADD FOREIGN KEY FK_VIK_RAID_VIK_RESPONSABLE_RAID (UTI_ID)
      REFERENCES VIK_RESPONSABLE_RAID (UTI_ID) ;


ALTER TABLE VIK_RAID 
  ADD FOREIGN KEY FK_VIK_RAID_VIK_CLUB (CLU_ID)
      REFERENCES VIK_CLUB (CLU_ID) ;


ALTER TABLE VIK_RESPONSABLE_COURSE 
  ADD FOREIGN KEY FK_VIK_RESPONSABLE_COURSE_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_RESPONSABLE_CLUB 
  ADD FOREIGN KEY FK_VIK_RESPONSABLE_CLUB_VIK_CLUB (CLU_ID)
      REFERENCES VIK_CLUB (CLU_ID) ;


ALTER TABLE VIK_RESPONSABLE_CLUB 
  ADD FOREIGN KEY FK_VIK_RESPONSABLE_CLUB_VIK_UTILISATEUR (UTI_ID)
      REFERENCES VIK_UTILISATEUR (UTI_ID) ;


ALTER TABLE VIK_APPARTIENT 
  ADD FOREIGN KEY FK_VIK_APPARTIENT_VIK_COUREUR (UTI_ID)
      REFERENCES VIK_COUREUR (UTI_ID) ;


ALTER TABLE VIK_APPARTIENT 
  ADD FOREIGN KEY FK_VIK_APPARTIENT_VIK_EQUIPE (RAI_ID,COU_ID,EQU_ID)
      REFERENCES VIK_EQUIPE (RAI_ID,COU_ID,EQU_ID) ;



-- ===================================================
-- SCRIPT COMPLET D'INSERTION - BASE DE DONNÉES MLR2
-- ===================================================
USE MLR2;

-- Désactivation des contraintes pour garantir un nettoyage propre
SET FOREIGN_KEY_CHECKS = 0;

-- ===================================================
-- 1. TABLES DE RÉFÉRENCE (SANS DÉPENDANCES)
-- ===================================================
INSERT INTO VIK_CLUB (CLU_ID, CLU_NOM, CLU_RUE, CLU_CODE_POSTAL, CLU_VILLE) VALUES
(1,'Club Rouen','1 Rue de la Paix','76000', 'Rouen'),
(2,'Club Caen','10 Avenue du Stade','14000', 'Caen'),
(3,'Club Lyon','5 Boulevard des Sports','69000', 'Lyon');

INSERT INTO VIK_COURSE_TYPE (TYP_ID, TYP_DESCRIPTION) VALUES
(1,'Course de vitesse'), (2,'Course d’endurance'), (3,'Course en relais'), (4,'Course en équipe'), (5,'Course enfants');

INSERT INTO VIK_TRANCHE_DIFFICULTE (DIF_NIVEAU, DIF_DESCRIPTION) VALUES
(1,'Débutant'), (2,'Intermédiaire'), (3,'Avancé'), (4,'Expert'), (5,'Elite');

-- ===================================================
-- 2. TABLE PARENTE : VIK_UTILISATEUR (50 membres + 1 Admin)
-- ===================================================
INSERT INTO vik_utilisateur
(UTI_ID, UTI_EMAIL, UTI_NOM, UTI_PRENOM, UTI_DATE_NAISSANCE, UTI_RUE, UTI_CODE_POSTAL, UTI_VILLE, UTI_TELEPHONE, UTI_LICENCE, UTI_NOM_UTILISATEUR, UTI_MOT_DE_PASSE)
VALUES
(1,'Robin.Paul@mail.fr','Paul','Robin','2005-08-08','Rue des Lilas','76000','Rouen','0600000018','18018','rpaul','pass123'),
(2,'Lemoine.Alice@mail.fr','Alice','Lemoine','2000-11-11','Avenue des Fleurs','14000','Caen','0600000021','21021','alemoine','pass123'),
(3,'Fabre.Leo@mail.fr','Leo','Fabre','1998-12-12','Boulevard du Parc','14000','Caen','0600000022','22022','lfabre','pass123'),
(4,'Carpentier.Eva@mail.fr','Eva','Carpentier','2001-01-05','Rue de l’Église','76000','Rouen','0600000023','23023','ecarpentier','pass123'),
(5,'Dupont.Noah@mail.fr','Noah','Dupont','2002-02-14','Impasse du Moulin','76000','Rouen','0600000024','24024','ndupont','pass123'),
(6,'Renaud.Lina@mail.fr','Lina','Renaud','2003-03-23','Rue des Érables','14000','Caen','0600000025','25025','lrenaud','pass123'),
(7,'Gautier.Hugo@mail.fr','Hugo','Gautier','1999-04-30','Chemin des Vignes','14000','Caen','0600000026','26026','hgautier','pass123'),
(8,'Morel.Zoé@mail.fr','Zoé','Morel','2004-05-15','Rue du Château','76000','Rouen','0600000027','27027','zmorel','pass123'),
(9,'Perrin.Théo@mail.fr','Théo','Perrin','2005-06-21','Rue des Cerisiers','76000','Rouen','0600000028','28028','tperrin','pass123'),
(10,'Marchand.Aurélie@mail.fr','Aurélie','Marchand','1997-07-07','Avenue Victor Hugo','14000','Caen','0600000029','29029','amarchand','pass123'),
(11,'Blanchard.Max@mail.fr','Max','Blanchard','2000-08-08','Rue du Pont','69000','Lyon','0600000030','30030','mblanchard','pass123'),
(12,'Dufour.Clara@mail.fr','Clara','Dufour','2001-09-09','Rue des Acacias','14000','Caen','0600000031','31031','cdufour','pass123'),
(13,'Rousseau.Jules@mail.fr','Jules','Rousseau','1998-10-10','Place de la Liberté','14000','Caen','0600000032','32032','jrousseau','pass123'),
(14,'Leclerc.Lina@mail.fr','Lina','Leclerc','2002-11-11','Rue des Tilleuls','76000','Rouen','0600000033','33033','lleclerc','pass123'),
(15,'Fayard.Lucas@mail.fr','Lucas','Fayard','1999-12-12','Boulevard Saint-Michel','76000','Rouen','0600000034','34034','lfayard','pass123'),
(16,'Noel.Eva@mail.fr','Eva','Noel','2000-01-01','Rue du Soleil','14000','Caen','0600000035','35035','enoel','pass123'),
(17,'Barbier.Tom@mail.fr','Tom','Barbier','2001-02-02','Rue des Peupliers','14000','Caen','0600000036','36036','tbarbier','pass123'),
(18,'Louis.Alice@mail.fr','Alice','Louis','2003-03-03','Rue des Marronniers','76000','Rouen','0600000037','37037','alouis','pass123'),
(19,'Guillaume.Noah@mail.fr','Noah','Guillaume','2002-04-04','Avenue de la Gare','76000','Rouen','0600000038','38038','nguillaume','pass123'),
(20,'Pons.Lina@mail.fr','Lina','Pons','2004-05-05','Rue des Bouleaux','14000','Caen','0600000039','39039','lpons','pass123'),
(21,'Martinez.Hugo@mail.fr','Hugo','Martinez','2000-06-06','Rue de la Fontaine','14000','Caen','0600000040','40040','hmartinez','pass123'),
(22,'Jacquet.Zoé@mail.fr','Zoé','Jacquet','2001-07-07','Rue des Ormes','76000','Rouen','0600000041','41041','zjacquet','pass123'),
(23,'Benoit.Théo@mail.fr','Théo','Benoit','1999-08-08','Chemin du Lac','76000','Rouen','0600000042','42042','tbenoit','pass123'),
(24,'Fournier.Aurélie@mail.fr','Aurélie','Fournier','2003-09-09','Rue du Stade','14000','Caen','0600000043','43043','afournier','pass123'),
(25,'Meyer.Max@mail.fr','Max','Meyer','2002-10-10','Rue de la Montagne','14000','Caen','0600000044','44044','mmeyer','pass123'),
(26,'Henry.Clara@mail.fr','Clara','Henry','2000-11-11','Avenue de la République','76000','Rouen','0600000045','45045','chenry','pass123'),
(27,'Legrand.Jules@mail.fr','Jules','Legrand','2001-12-12','Rue des Roses','76000','Rouen','0600000046','46046','jlegrand','pass123'),
(28,'Lopez.Lina@mail.fr','Lina','Lopez','2004-01-01','Rue de la Paix','14000','Caen','0600000047','47047','llopez','pass123'),
(29,'Riviere.Hugo@mail.fr','Hugo','Riviere','2002-02-02','Impasse des Fleurs','14000','Caen','0600000048','48048','hriviere','pass123'),
(30,'Olivier.Zoé@mail.fr','Zoé','Olivier','2003-03-03','Rue du Verger','76000','Rouen','0600000049','49049','zolivier','pass123'),
(31,'Fontaine.Théo@mail.fr','Théo','Fontaine','2000-04-04','Boulevard du Nord','76000','Rouen','0600000050','50050','tfontaine','pass123'),
(32,'Dupuis.Marie@mail.fr','Marie','Dupuis','2003-05-05','Rue des Lilas Bleus','14000','Caen','0600000051','51051','mdupuis','pass123'),
(33,'Marchal.Léo@mail.fr','Léo','Marchal','2001-06-06','Rue de l’École','76000','Rouen','0600000052','52052','lmarchal','pass123'),
(34,'Fernandez.Alice@mail.fr','Alice','Fernandez','2002-07-07','Rue des Primevères','14000','Caen','0600000053','53053','afernandez','pass123'),
(35,'Garnier.Noah@mail.fr','Noah','Garnier','2004-08-08','Chemin des Pins','76000','Rouen','0600000054','54054','ngarnier','pass123'),
(36,'Chevalier.Lina@mail.fr','Lina','Chevalier','2003-09-09','Rue de la Plage','14000','Caen','0600000055','55055','lchevalier','pass123'),
(37,'Colin.Hugo@mail.fr','Hugo','Colin','2000-10-10','Rue des Violettes','76000','Rouen','0600000056','56056','hcolin','pass123'),
(38,'Blondel.Eva@mail.fr','Eva','Blondel','2002-11-11','Rue de la Gare','14000','Caen','0600000057','57057','eblondel','pass123'),
(39,'Baron.Max@mail.fr','Max','Baron','2001-12-12','Avenue des Champs','76000','Rouen','0600000058','58058','mbaron','pass123'),
(40,'Philippe.Alice@mail.fr','Alice','Philippe','2004-01-01','Rue du Levant','14000','Caen','0600000059','59059','aphilippe','pass123'),
(41,'Perrier.Noah@mail.fr','Noah','Perrier','2000-02-02','Rue du Marché','76000','Rouen','0600000060','60060','nperrier','pass123'),
(42,'Guillet.Lina@mail.fr','Lina','Guillet','2003-03-03','Rue des Hortensias','14000','Caen','0600000061','61061','lguillet','pass123'),
(43,'Renard.Hugo@mail.fr','Hugo','Renard','2001-04-04','Boulevard de l’Ouest','76000','Rouen','0600000062','62062','hrenard','pass123'),
(44,'Henry.Léo@mail.fr','Léo','Henry','2002-05-05','Rue de la Croix','14000','Caen','0600000063','63063','lhenry','pass123'),
(45,'Mallet.Alice@mail.fr','Alice','Mallet','2003-06-06','Rue des Jonquilles','76000','Rouen','0600000064','64064','amallet','pass123'),
(46,'Adam.Noah@mail.fr','Noah','Adam','2000-07-07','Rue du Jardin','14000','Caen','0600000065','65065','nadam','pass123'),
(47,'Lucas.Hugo@mail.fr','Hugo','Lucas','2001-08-08','Rue de la Mare','76000','Rouen','0600000066','66066','hlucas','pass123'),
(48,'Giraud.Eva@mail.fr','Eva','Giraud','2002-09-09','Avenue des Écoles','14000','Caen','0600000067','67067','egiraud','pass123'),
(49,'Pierre.Lina@mail.fr','Lina','Pierre','2004-10-10','Rue des Peupliers Blancs','76000','Rouen','0600000068','68068','lpierre','pass123'),
(50,'Martin.Hugo@mail.fr','Hugo','Martin','2003-11-11','Rue de la Source','14000','Caen','0600000069','69069','hmartin','pass123'),
(100,'admin.vikazim@mail.fr','Admin','Vikazim','1980-01-01','Rue des Lilas','76000','Rouen','0600000000','10000','admin_sys','Root123!');


-- ===================================================
-- 3. TABLES SPÉCIALISÉES (HÉRITAGE TOTAL) - CORRIGÉ
-- ===================================================

-- ADMINISTRATEURS (Structure identique à VIK_UTILISATEUR)
INSERT INTO VIK_ADMINISTRATEUR
(
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
)
SELECT
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
FROM VIK_UTILISATEUR
WHERE UTI_ID = 100;


-- COUREURS (Ajout manuel de CLU_ID car non présent dans le parent)
INSERT INTO VIK_COUREUR (UTI_ID, UTI_EMAIL, UTI_NOM, UTI_PRENOM, UTI_DATE_NAISSANCE, UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE , UTI_TELEPHONE, UTI_LICENCE, UTI_NOM_UTILISATEUR, UTI_MOT_DE_PASSE, CLU_ID, CRR_PPS)
SELECT UTI_ID, UTI_EMAIL, UTI_NOM, UTI_PRENOM, UTI_DATE_NAISSANCE, UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE , UTI_TELEPHONE, UTI_LICENCE, UTI_NOM_UTILISATEUR, UTI_MOT_DE_PASSE, 
       (UTI_ID % 3) + 1, NULL 
FROM VIK_UTILISATEUR WHERE UTI_ID <= 50;

-- RESPONSABLES CLUB (Ajout manuel de CLU_ID)
INSERT INTO VIK_RESPONSABLE_CLUB (UTI_ID, UTI_EMAIL, UTI_NOM, UTI_PRENOM, UTI_DATE_NAISSANCE, UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE , UTI_TELEPHONE, UTI_LICENCE, UTI_NOM_UTILISATEUR, UTI_MOT_DE_PASSE, CLU_ID)
SELECT UTI_ID, UTI_EMAIL, UTI_NOM, UTI_PRENOM, UTI_DATE_NAISSANCE, UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE , UTI_TELEPHONE, UTI_LICENCE, UTI_NOM_UTILISATEUR, UTI_MOT_DE_PASSE, 1
FROM VIK_UTILISATEUR WHERE UTI_ID = 2;

-- RESPONSABLES RAID (Structure identique à VIK_UTILISATEUR)
INSERT INTO VIK_RESPONSABLE_RAID
(
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
)
SELECT
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
FROM VIK_UTILISATEUR
WHERE UTI_ID in (5, 6);


-- RESPONSABLES COURSE (Structure identique à VIK_UTILISATEUR)
INSERT INTO VIK_RESPONSABLE_COURSE
(
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
)
SELECT
  UTI_ID,
  UTI_EMAIL,
  UTI_NOM,
  UTI_PRENOM,
  UTI_DATE_NAISSANCE,
  UTI_RUE ,
   UTI_CODE_POSTAL ,
   UTI_VILLE ,
  UTI_TELEPHONE,
  UTI_LICENCE,
  UTI_NOM_UTILISATEUR,
  UTI_MOT_DE_PASSE
FROM VIK_UTILISATEUR
WHERE UTI_ID in (7, 8);

-- ===================================================
-- 4. ÉVÉNEMENTS : RAIDS ET COURSES
-- ===================================================
INSERT INTO VIK_RAID (RAI_ID, CLU_ID, UTI_ID, RAI_NOM, RAI_RAID_DATE_DEBUT, RAI_RAID_DATE_FIN, RAI_INSCRI_DATE_DEBUT, RAI_INSCRI_DATE_FIN, RAI_CONTACT, RAI_WEB, RAI_LIEU, RAI_IMAGE)
VALUES
(1,1,5,'Raid Normandie 2026','2026-03-10 08:00:00','2026-03-10 18:00:00','2026-02-01 00:00:00','2026-03-09 23:59:59','contact@vikazim.fr','www.vikazim.fr','Rouen','raid1.png'),
(2,2,6,'Raid Caen 2026','2026-04-15 09:00:00','2026-04-15 17:00:00','2026-03-01 00:00:00','2026-04-14 23:59:59','contact@caenclub.fr','www.caenclub.fr','Caen','raid2.png');

INSERT INTO VIK_COURSE (RAI_ID, COU_ID, TYP_ID, DIF_NIVEAU, UTI_ID, COU_NOM, COU_DATE_DEBUT, COU_DATE_FIN, COU_PRIX, COU_PRIX_ENFANT, COU_PARTICIPANT_MIN, COU_PARTICIPANT_MAX, COU_EQUIPE_MIN, COU_EQUIPE_MAX, COU_PARTICIPANT_PAR_EQUIPE_MAX, COU_REPAS_PRIX, COU_REDUCTION, COU_LIEU, COU_AGE_MIN, COU_AGE_SEUL, COU_AGE_ACCOMPAGNATEUR)
VALUES
(1,1,1,1,7,'Course Vitesse Rouen','2026-03-10 08:30:00','2026-03-10 10:30:00',15.00,10.00,5,50,1,10,5,5.00,0.0,'Forêt de Rouen',12,16,18),
(1,2,2,2,8,'Course Endurance Rouen','2026-03-10 11:00:00','2026-03-10 14:00:00',20.00,12.00,5,40,2,8,4,7.00,0.0,'Forêt de Rouen',14,18,20),
(2,1,3,3,7,'Course Relais Caen','2026-04-15 09:30:00','2026-04-15 12:30:00',25.00,15.00,6,60,2,12,5,8.00,0.0,'Parc de Caen',16,20,22),
(2,2,4,2,8,'Course Equipe Caen','2026-04-15 13:00:00','2026-04-15 16:00:00',18.00,10.00,4,50,2,10,4,6.00,0.0,'Parc de Caen',12,18,20);

-- ===================================================
-- 5. EQUIPES ET APPARTENANCE
-- ===================================================
INSERT INTO VIK_EQUIPE (RAI_ID, COU_ID, EQU_ID, UTI_ID) VALUES 
(1,1,1,1), (1,1,2,3), (1,2,1,5), (2,1,1,9), (2,2,1,11);

INSERT INTO VIK_APPARTIENT (UTI_ID, RAI_ID, COU_ID, EQU_ID) VALUES
(1,1,1,1),(2,1,1,1),
(3,1,1,2),(4,1,1,2),
(5,1,2,1),(6,1,2,1),
(9,2,1,1),(10,2,1,1),
(11,2,2,1),(12,2,2,1);

-- ===================================================
-- 6. RESULTATS
-- ===================================================
INSERT INTO VIK_RESULTAT (RAI_ID, COU_ID, EQU_ID, RES_RANG, RES_TEMPS, RES_POINT) VALUES
(1,1,1,'1','00:45:12',100),
(1,1,2,'2','00:50:30',90),
(1,2,1,'1','01:15:00',100),
(2,1,1,'1','01:10:15',100);
