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
numCom int,
dateValidate date,
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
create TRIGGER `UpdateCoutCMPU2` BEFORE UPDATE ON Designation
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
