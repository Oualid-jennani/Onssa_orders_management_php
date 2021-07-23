create database gestionStock;

use gestionStock;

create table magasin(idM smallint auto_increment primary key,MGname nvarchar(255) not null,MGcode nvarchar(30),activation bool default true);

create table famille(idF smallint auto_increment primary key,FMname nvarchar(255) not null,idM smallint,activation bool default true, FOREIGN KEY (idM) REFERENCES magasin(idM));

create table stock(idST smallint auto_increment primary key,STname nvarchar(255) not null,idM smallint,activation bool default true, FOREIGN KEY (idM) REFERENCES magasin(idM));

create table categorie (idC smallint auto_increment primary key,CTname nvarchar(255) not null, idF smallint,activation bool default true, FOREIGN KEY (idF) REFERENCES  famille(idF) on delete cascade);

create table UniteMesure(idUM smallint auto_increment primary key,UMname nvarchar(60) not null);

create table Designation (
idDS smallint auto_increment primary key,
DSname nvarchar(255) not null,
image nvarchar(255),price float,
idC smallint,idUM smallint,
idST smallint,DScode nvarchar(30),
DSquantite int default 0,
coutStock float,
activation bool default true,
Val float default 0,
demande bool default false,
FOREIGN KEY (idC) REFERENCES  categorie(idC) on delete cascade,
FOREIGN KEY (idUM) REFERENCES  UniteMesure(idUM) on delete cascade,
FOREIGN KEY (idST) REFERENCES  stock(idST) on delete cascade
);



create table services(idSR smallint auto_increment primary key,SRname nvarchar(255) not null ,activation bool default true);

create table admin( idAd smallint auto_increment primary key,Cnt nvarchar(60) not null , Adname nvarchar(255) not null,Adpass nvarchar(255) not null,typeAD nvarchar(50),idM smallint ,idSR smallint,FOREIGN KEY (idM) REFERENCES magasin(idM) on delete cascade,FOREIGN KEY (idSR) REFERENCES  services(idSR) on delete cascade);

create table client(idCL smallint auto_increment primary key,Cnt nvarchar(60) not null,CLname nvarchar(255) not null,CLpass nvarchar(255) not null,idSR smallint,FOREIGN KEY (idSR) REFERENCES  services(idSR) on delete cascade);

create table versionImpriment( idVR smallint auto_increment primary key , nameVersion nvarchar(60) );

insert into versionImpriment ( nameVersion ) values ('vertion A');

create table commandes(
idCM int auto_increment primary key, 
idCL smallint not null,
dateCom date,
delaiO  nvarchar(20),
validate nvarchar(30) not null default "en cours",
confirmation bool not null default false,
validateGest bool not null default false,
NewvalidateGest bool not null default false,
idM smallint not null,
numCom varchar(50),
FOREIGN KEY (idM) REFERENCES  magasin(idM) on delete cascade,
FOREIGN KEY (idCL) REFERENCES  client(idCL) on delete cascade
);

create table ligneCommande(
idCM int not null,
idDS smallint not null,
qtD smallint,
qtA smallint,
accorder bool not null default true,
FOREIGN KEY (idCM) REFERENCES  commandes(idCM) on delete cascade,
FOREIGN KEY (idDS) REFERENCES  Designation(idDS) on delete cascade,
updateLigne bool not null default false,
primary key(idCM,idDS)
);

create table TempligneCommande(
idCM int not null,
idDS smallint not null,
qtD smallint,
qtA smallint,
accorder bool not null default true,
FOREIGN KEY (idCM) REFERENCES  commandes(idCM) on delete cascade,
FOREIGN KEY (idDS) REFERENCES  Designation(idDS) on delete cascade,
updateLigne bool not null default false,
primary key(idCM,idDS)
);

/*---------------------------calucul montant---------------------------------*/
delimiter $$
create TRIGGER `UpdateCoutCMPU` BEFORE INSERT ON Designation
FOR EACH ROW BEGIN
   set New.coutStock = NEW.DSquantite * NEW.price;
END$$



delimiter $$
create PROCEDURE insertCommande (id_CL smallint,date_Com date,delai_O nvarchar(20),id_M smallint)
BEGIN
	DECLARE Num_Com smallint;
    if ((select count(*) from commandes where idM = id_M)!=0) then
		set Num_Com = (select max(numCom) from commandes where idM = id_M)+1;
    else 
		set Num_Com =1;
    end if;

    insert into commandes (idCL,dateCom,delaiO,idM,numCom) values (id_CL,date_Com,delai_O,id_M,Num_Com );
END$$



delimiter $$
create PROCEDURE insertLigneCom (id_CM smallint,id_DS smallint ,quantiteA smallint)
BEGIN
    insert into ligneCommande (idCM,idDS,qtD,qtA) values (id_CM,id_DS,quantiteA,quantiteA);
    insert into TempligneCommande (idCM,idDS,qtD,qtA) values (id_CM,id_DS,quantiteA,quantiteA);
    update Designation set demande = true where idDS = id_DS;
END$$





























/*---------------------------EntreeQuantite---------------------------------*/
create table BonCommande(
idBC int auto_increment primary key,
N_BC nvarchar(100),
dateEntree date,
updateBon bool default false,
idM smallint,
FOREIGN KEY (idM) REFERENCES  magasin(idM) on delete cascade
);

create table ligneBonCommande(
idBC int not null,
idDS smallint not null,
qtEntree smallint,
newPrix float ,
FOREIGN KEY (idBC) REFERENCES  BonCommande(idBC) on delete cascade,
FOREIGN KEY (idDS) REFERENCES  Designation(idDS) on delete cascade,
primary key(idBC,idDS)
);

delimiter $$
create PROCEDURE insertBonCommande (id_BC int,id_DS smallint ,qtEntree smallint,newPrix float)
BEGIN
    insert into ligneBonCommande(idBC,idDS,qtEntree,newPrix) values(id_BC,id_DS,qtEntree,newPrix);
END$$
/*---------------------------EntreeQuantite---------------------------------*/

update commandes set validate = "pret a livrer" where idCM <> 100;
insert into BonAppr(idCM,dateSortie) values (4,"2020-03-18");

/*---------------------------SortieQuantitee---------------------------------*/
create table BonAppr(
idBA int auto_increment primary key,
idCM int,/*num commande*/
dateSortie date,
updateBon bool default false,
FOREIGN KEY (idCM) REFERENCES  commandes(idCM) on delete cascade
);
update BonAppr set dateSortie = "" , updateBon = true where idBA = 

create table ligneBonAppr(
idBA int not null,
idDS smallint not null,
qtSortie smallint,
FOREIGN KEY (idBA) REFERENCES  BonAppr(idBA) on delete cascade,
FOREIGN KEY (idDS) REFERENCES  Designation(idDS) on delete cascade,
primary key(idBA,idDS)
);

delimiter $$
create PROCEDURE insertligneBonAppr (id_BA int,id_DS smallint ,qtSortie smallint)
BEGIN
    insert into ligneBonAppr(idBA,idDS,qtSortie) values(id_BA,id_DS,qtSortie);
END$$
/*---------------------------SortieQuantitee---------------------------------*/



-- *************************************  entre && sortie triggers *****************************************


delimiter $$
create TRIGGER `CurligneBonAppr` After INSERT ON BonAppr
FOR EACH ROW BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE id_DS int;
    DECLARE qtSortie int;
    
    -- declare cursor for bon appr
    DEClARE cur CURSOR FOR SELECT idDS,qtA FROM ligneCommande where idCM = new.idCM and accorder = true ;
    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET finished = 1;
	OPEN cur;

    setLigneApr: LOOP
        FETCH cur INTO id_DS,qtSortie;
        IF finished = 1 THEN 
            LEAVE setLigneApr;
        END IF;
        -- code execut
        insert into ligneBonAppr(idBA,idDS,qtSortie) values(new.idBA,id_DS,qtSortie);
    END LOOP setLigneApr;
    CLOSE cur;

END$$



delimiter $$
create TRIGGER `EntreeQuantite` BEFORE INSERT ON ligneBonCommande
FOR EACH ROW BEGIN

	DECLARE Old_Qt int;
    DECLARE UppdateQT int;

    DECLARE Old_coutSt float;
    DECLARE UppdatecoutSt float;
    DECLARE UppdateCMUP float;
    
	set Old_Qt = (select DSquantite from Designation where idDS= NEW.idDS);
	set UppdateQT = Old_Qt + New.qtEntree;
    
    
    set Old_coutSt = (select coutStock from Designation where idDS= NEW.idDS);
    set UppdatecoutSt = Old_coutSt + (New.newPrix * New.qtEntree);
    
    set UppdateCMUP = UppdatecoutSt / UppdateQT;
    
	
    if (New.qtEntree <= 0 or New.newPrix <= 0) then
		SIGNAL SQLSTATE '02000' SET MESSAGE_TEXT = 'Error qtEntree or Price';
	else 
        update Designation set DSquantite = UppdateQT , coutStock = UppdatecoutSt , price = UppdateCMUP  where idDS= NEW.idDS;
    end if;
END$$



delimiter $$
create TRIGGER `SortieQuantitee` BEFORE INSERT ON ligneBonAppr
FOR EACH ROW BEGIN
	DECLARE Old_Qt int;
    DECLARE UppdateQT int;
    
    DECLARE UppdatecoutSt float;
    DECLARE Old_price float;
    
    set Old_Qt = (select DSquantite from Designation where idDS= NEW.idDS);
	set UppdateQT = Old_Qt - New.qtSortie;
	
    
    set Old_price = (select price from Designation where idDS = NEW.idDS);
    set UppdatecoutSt = (UppdateQT * Old_price) ;
    
    if (New.qtSortie <= 0 or UppdateQT <0) then
		SIGNAL SQLSTATE '03000' SET MESSAGE_TEXT = 'Error qtEntree.';
	else 
        update Designation set DSquantite = UppdateQT , coutStock = UppdatecoutSt where idDS= NEW.idDS;
    end if;
END$$

update Designation set DSquantite = 50 where idDS <> 2000;









/*----------------------- insertion -------------------------------*/

 -- *************************************************************************************
insert into magasin(MGname,MGcode)values('Magasins du SRS','SRS'),('Magasin de la division vétérinaire','MDV'),('Magasin de la division Protection des végétaux','MPV');
 -- *************************************************************************************


 -- *************************************************************************************
insert into stock (STname,idM) values ('s1',1),('s2',1),('s1',2),('s2',2),('s1',3),('s2',3);
 -- *************************************************************************************
 
  
-- *************************************************************************************
insert into famille(FMname,idM) values
('Fournitures informatiques',1),("Produits d'hygiène",1),('Fournitures bureautique',1),
('Fourniture de prélèvement et consommables',2),("Produits d'Hygiène DV",2),('HABILLEMENT TENUS DE PROTECTION',2),
('PRODUITS PHYTOSANITAIRES',3),("PRODUITS BIOLOGIQUES ",3);

-- *************************************************************************************

-- *************************************************************************************
insert into categorie(CTname,idF) values("Tonners imprimantes",1),("Tonners fax et photocopieurs",1),("Logiciels et supports ",1);
insert into categorie(CTname,idF) values("categorie 1 (Produi D'hyginéne)",2),("categorie 2 (Produi D'hyginéne)",2);
insert into categorie(CTname,idF) values("Ecriture",3),("Papier",3),("Enveloppes",3),("Classement",3),("Relliure",3),("Accessoirs",3);
insert into categorie(CTname,idF) values("categorie 1 (F de P et C)",4),("categorie 2 (F de P et C)",4);
insert into categorie(CTname,idF) values("categorie 1 (Produits d'Hygiène DV)",5),("categorie 2 (Produits d'Hygiène DV)",5);
insert into categorie(CTname,idF) values("categorie 1 (H T de P)",6),("categorie 2 (H T de P)",6);
insert into categorie(CTname,idF) values("categorie 1 (P PH)",7),("categorie 2 (P PH)",7);
insert into categorie(CTname,idF) values("categorie 1 (test)",8),("categorie 1 (test)",8);

-- *************************************************************************************
insert into UniteMesure(UMname) values("u"),('Rouleau'),('Boite'),("Boite de 100 u"),('Flacon 1L'),
("L"),("10PCs"),('Flacon'),('Pochette'),("sachet"),('kg'),("colis"),("Paquets"),('Rame'),('Carton'),
("boite de 100 unités"),("Flacon DE 300m"),("Flacon de 5L"),('Flacon DE 700ml'),('Paquet de 15 unités'),
("sachet de 375g"),('bidon 25l'),('paire');
-- *************************************************************************************



-- *************************************************************************************
-- cat1 --Tonners imprimantes
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("UNI 85/35/36","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti1'),
("W-Q5949A/7081","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti2'),
("Q5949A/Q7553A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti3'),
("12A/FX10","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti4'),
("HP CF283A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti5'),
("CF283A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti6'),
("CC530A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti7'),
("CC531A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti8'),
("CC532A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti9'),
("CC533A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti10'),
("CF283X/737","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti11'),
("CF410A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti12'),
("CF411A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti13'),
("CF412A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti14'),
("CF413A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti15'),
("CF414A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti16'),
("W-C4092A/EP22","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti17'),
("C4092A/EP22","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti18'),
("7553A/Q5949A","images\Images_Designation\dist_test.jpg",100.6,1,1,1,'ti19');
-- cat2 --Tonners fax et photocopieurs
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("XEROX 5222/5230","images\Images_Designation\dist_test.jpg",100.6,2,1,1,'tp1'),
("XEROX 5222/5225/5230","images\Images_Designation\dist_test.jpg",100.6,2,1,1,'tp2'),
("CANON C-EXV14 BLACK","images\Images_Designation\dist_test.jpg",100.6,2,1,1,'tp3'),
("CANON C-EXV40 BLACK","images\Images_Designation\dist_test.jpg",100.6,2,1,1,'tp4');
-- cat3 --Logiciels et supports
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Antivirus KasperSky","images\Images_Designation\dist_test.jpg",100.6,3,7,1,'ls1'),
("USB FLASH DEVICE 32GB","images\Images_Designation\dist_test.jpg",100.6,3,1,1,'ls2'),
("CABLE IMPRIMANTE 3M","images\Images_Designation\dist_test.jpg",100.6,3,1,1,'ls3'),
("POCHETTE CD","images\Images_Designation\dist_test.jpg",100.6,3,1,1,'ls4'),
("CD vierge","images\Images_Designation\dist_test.jpg",100.6,3,1,1,'ls5');
-- cat4 --categorie 1 (Produi D'hyginéne)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("balai de plafond","images\Images_Designation\dist_test.jpg",100.6,4,1,1,'c1pd1'),
("Bloc wc antitartre à supendreeau bleu","images\Images_Designation\dist_test.jpg",100.6,4,3,1,'c1pd2');
-- cat5 --categorie 2 (Produi D'hyginéne)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Brosse wc","images\Images_Designation\dist_test.jpg",100.6,5,1,1,'c2pd1'),
("Détergent Citron en poudre 180g","images\Images_Designation\dist_test.jpg",100.6,5,1,1,'c2pd2');
 -- cat6 --Ecriture
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Brosse pour tableau paquet de 12 unités","images\Images_Designation\dist_test.jpg",100.6,6,1,1,'e1'),
("Correcteur ruban","images\Images_Designation\dist_test.jpg",100.6,6,1,1,'e2'),
("Dateur automatique en français 'Trodat' réf 4810 ou similaire","images\Images_Designation\dist_test.jpg",100.6,6,1,1,'e3');
-- cat7 --Papier
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Bloc note GF 200 Pages colis de 30 unité","images\Images_Designation\dist_test.jpg",100.6,7,12,1,'p1'),
("Papier  ministre","images\Images_Designation\dist_test.jpg",100.6,7,13,1,'p2');
-- cat8 --Enveloppes
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Enveloppes blanches F21- 90 grs ","images\Images_Designation\dist_test.jpg",100.6,8,1,1,'en1'),
("Enveloppes Kraft F81- 90 grs ","images\Images_Designation\dist_test.jpg",100.6,8,1,1,'en2');
-- cat9 --Classement
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Boite archive en polypropylène 14cm ; 25*35 cm de différentes couleurs","images\Images_Designation\dist_test.jpg",100.6,9,1,1,'cl1'),
("Chemise à sangle plein toile","images\Images_Designation\dist_test.jpg",100.6,9,13,1,'cl2');
-- cat10 --Relliure
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Agrafes métallique 23/13","images\Images_Designation\dist_test.jpg",100.6,10,3,1,'r1'),
("Agrafeuse à main 24/6 inox PRIMULA","images\Images_Designation\dist_test.jpg",100.6,10,1,1,'r2');
-- cat11 --Accessoirs
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Accessoire de bureau en metal","images\Images_Designation\dist_test.jpg",100.6,11,1,3,'ac1'),
("Lame ouvre lettre","images\Images_Designation\dist_test.jpg",100.6,11,1,3,'ac2'),
("Kutter GM","images\Images_Designation\dist_test.jpg",100.6,11,1,3,'ac3'),
("Lame ouvre lettre","images\Images_Designation\dist_test.jpg",100.6,11,1,3,'ac4');
-- cat12 --categorie 1 (F de P et C)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("SAC D'ECHANTILLONNAGE ATL (18/25 cm)","images\Images_Designation\dist_test.jpg",100.6,12,1,3,'cf1p1'),
("FLACON STERILE AVEC THIOSULFATE 1 litre ","images\Images_Designation\dist_test.jpg",100.6,12,5,3,'cf1p2');
-- cat13 --categorie 2 (F de P et C)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("crayon a marquage les bistiaux(boite de 10u)","images\Images_Designation\dist_test.jpg",100.6,13,3,3,'cf2p1'),
("Etiquettes des echantillons (paquet de 1000)","images\Images_Designation\dist_test.jpg",100.6,13,13,3,'cf2p2');
-- cat14 --categorie 1 (Produits d'Hygiène DV)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Lessive en poudre ","images\Images_Designation\dist_test.jpg",100.6,14,11,3,'cp1dv1'),
("sachet de 375g","images\Images_Designation\dist_test.jpg",100.6,14,21,3,'cp1dv2');
-- cat15 --categorie 2 (Produits d'Hygiène DV)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("papier hygenique ","images\Images_Designation\dist_test.jpg",100.6,15,8,3,'cp2dv1'),
("gel antiseptique","images\Images_Designation\dist_test.jpg",100.6,15,16,3,'cp2dv2');
-- cat16 --categorie 1 (H T de P)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Alcool médical (Flacon de 1L)","images\Images_Designation\dist_test.jpg",100.6,16,5,3,'ch1p1'),
("gresil 1L","images\Images_Designation\dist_test.jpg",100.6,16,1,3,'ch1p2');
-- cat17 --categorie 2 (H T de P)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("Botte","images\Images_Designation\dist_test.jpg",100.6,17,23,5,'ch2p1'),
("surchausse","images\Images_Designation\dist_test.jpg",100.6,17,16,5,'ch2p2');
-- cat18 --categorie 1 (P PH)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("chlorpyriphos ethyl 480g/l","images\Images_Designation\dist_test.jpg",100.6,18,7,5,'cp1ph1'),
("imidachlopride 350g/l","images\Images_Designation\dist_test.jpg",100.6,18,7,5,'cp1ph2');
-- cat19 --categorie 1 (P PH)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("HUILE MINERALE","images\Images_Designation\dist_test.jpg",100.6,19,22,5,'cp2ph1'),
("PYRIPROXIFENE 100 G/L","images\Images_Designation\dist_test.jpg",100.6,19,6,5,'cp2ph1');
-- cat20 --categorie 1 (P PH)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("CAPSULE CRP","images\Images_Designation\dist_test.jpg",100.6,20,1,5,'ct1'),
("CAPSULE SCOLYTE","images\Images_Designation\dist_test.jpg"
,100.6,20,1,5,'ct2');
-- cat21 --categorie 1 (P PH)
insert into Designation(DSname,image,price,idC,idUM,idST,DScode) values
("CAPSULE MOUCHE OLIVE","images\Images_Designation\dist_test.jpg",100.6,21,1,5,'c2t1'),
("CAPSULE CERARITE ","images\Images_Designation\dist_test.jpg",100.6,21,1,5,'c2t2');

update Designation set image = "images\\Images_Designation\\dist_test.png" where idDS <> 1000;

-- *****************************************************************************************--


insert into services(SRname) values ("service 1"),("service 2"),("service 3");

-- *************************************************************************************
insert into client(CNT,CLname,CLpass,idSR)values('abcd','walid','1234',1);
-- *************************************************************************************
INSERT INTO `admin` (`Cnt`, `Adname`, `Adpass`, `typeAD`, `idM`, `idSR`) VALUES
('chef', 'walid', 'chef', 'chef', 1, 1),
('gestionnaire', 'walid', 'gestionnaire', 'gestionnaire', 1, 1),
('magasinier', 'walid', 'magasinier', 'magasinier', 1, 1),
('admin', 'walid', 'admin', 'admin', 1, 1);

-- *************************************************************************************
