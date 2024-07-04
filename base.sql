create database madaimmo;
\c madaimmo;

insert into profils(role,login,pwd) values('admin','admin','admin123');

create table if not exists profils(
    id serial primary key,
    role varchar(30),
    login varchar(30),
    pwd varchar(30)
);


create table if not exists typebien(
    id serial primary key,
    type varchar(30),
    commission integer
);


create table if not exists biens(
    id serial primary key,
    idtype integer references typebien(id),
    idproprio integer references profils(id),
    reference varchar(30),
    nom varchar(30),
    description varchar(50),
    region varchar(30),
    loyer double precision,
    photos varchar(30)
);

create table if not exists biens_img(
    id serial primary key,
    idbien integer references biens(id),
    url varchar(50)
);

create table if not exists location(
    id serial primary key,
    idclient integer references profils(id),
    idbien integer references biens(id),
    duree double precision,
    datedebut date
);

CREATE TABLE IF NOT EXISTS location_details (
    id SERIAL PRIMARY KEY,
    idbien INTEGER REFERENCES biens(id),
    idlocation INTEGER REFERENCES location(id),
    loyer DOUBLE PRECISION,
    commission DOUBLE PRECISION,
    datedebut DATE,
    datefin DATE,
    rang integer,
    duree double precision
);
create table if not exists paiement(
    id serial primary key,
    idlocation integer references location(id),
    montant double precision,
    etat boolean default false
)

CREATE OR REPLACE FUNCTION public.insert_location_details_trigger()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
    -- Insérez ici le code pour insérer les détails de location basé sur NEW
    INSERT INTO location_details (idbien,idlocation, loyer, commission, datedebut, datefin, rang, duree)
    SELECT b.id,NEW.id, b.loyer, ty.commission, NEW.datedebut, 
           (NEW.datedebut + interval '1 month' * (rang - 1))::date AS datefin, rang, NEW.duree
    FROM generate_series(1, NEW.duree) AS rang
    CROSS JOIN biens b
    JOIN typebien ty ON b.idtype = ty.id
    WHERE b.id = NEW.idbien;
    RETURN NEW;
END;
$function$
;

CREATE TRIGGER trigger_insert_location_details
AFTER INSERT ON public.location 
FOR EACH ROW
 EXECUTE PROCEDURE insert_location_details_trigger()

-- create view v_revenu_commission as
--  select ty.type,b.reference,b.nom,b.description,b.region,b.url,b.loyer,
--     ((b.loyer * ty.commission)/100) as revenu ,
--     (((b.loyer * ty.commission)/100) * lo.duree) as revenu_total 
--     from biens as b 
--     left join typebien ty on ty.id=b.idtype 
--     left join location lo on lo.idbien=b.id;

CREATE INDEX idx_location_details_datedebut ON location_details (datedebut);

-- Index sur `datefin`
CREATE INDEX idx_location_details_datefin ON location_details (datefin);

-- Index combiné sur `datedebut` et `datefin`
CREATE INDEX idx_location_details_datedebut_datefin ON location_details (datedebut, datefin);

create view v_biens as 
     SELECT b.id AS idbien,
    ty.id AS idtype,
    ty.type,
    p.login,
    b.reference,
    b.nom,
    b.description,
    b.region,
    b.loyer,
    bm.url AS photos
   FROM (((biens b
     LEFT JOIN typebien ty ON ((ty.id = b.idtype)))
     LEFT JOIN profils p ON ((p.id = b.idproprio)))
     LEFT JOIN biens_img bm ON ((bm.idbien = b.id)));

create view v_paiement_location as
    select pa.id as idpaiement,p.id as idclient,p.login,vb.type,
    vb.reference,vb.nom,vb.description,vb.region,vb.loyer,lo.duree,
    pa.montant,(vb.loyer * duree) as totalite,
    ((vb.loyer * duree)-pa.montant) as restetotalite,pa.etat
    from location lo
    left join paiement pa on pa.idlocation = lo.id 
    left join profils p on p.id=lo.idclient
    left join v_biens vb on vb.idbien=lo.idbien;

create view v_location_client as
    select lo.id,b.id as idbien,lo.idclient,p.login,ty.type,b.reference,b.nom,
    b.description,b.region,b.loyer,bm.url 
    from location lo 
    left join profils p on p.id=lo.idclient 
    left join biens b on b.id= lo.idbien 
    left join biens_img bm on bm.idbien=b.id
    left join typebien ty on ty.id=b.idtype;

create view v_final as select ld.rang as rang_mois,b.reference,b.nom,ld.duree,ld.commission,ld.datedebut,ld.datefin,
    CASE
        WHEN ld.rang=1 then ld.loyer
        ELSE (ld.loyer * ld.commission /100 )
    END AS gain_admin,
    CASE 
        WHEN ld.rang=1 then ld.loyer
        ELSE (ld.loyer - ((ld.loyer * ld.commission)/100))
    END AS gain_proprio,
    CASE 
        WHEN ld.rang=1 then ld.loyer*2
        ELSE ld.loyer
    END AS loyerparmois 
FROM location_details ld
   left join biens b on ld.idbien = b.id
    order by ld.datedebut;

insert into typebien(type,commission) values('Maison',20),('Appartement',20),('Villa',30);
insert into biens(idtype,idproprio,nom,description,region,loyer,photos) values(1,2,'Maison T2','2 chambres et 1 salle de bain','Mandrimena','120000','maison1.jpg'),(3,2,'Villa T3','Villa basse avec 3 chambres','Iavoloha','360000','villa1.jpg');
insert into location(idclient,idbien,duree,datedebut) values(3,1,'2','2024-06-03'),(3,2,'3','2024-06-10');
-- create table if not exists paiement(
--     id serial primary key,
--     idlocation integer references location(id),
--     paye boolean default false,

-- )

insert into biens_img(idbien,url) values 
(1,'maison1.jpg'),(1,'maison2.jpg'),
(2,'villa.jpg'),(2,'villa1.jpg'),
(3,'contemp.jpg'),(3,'contemp2.jpg'),
(4,'imm.jpg'),(4,'imm2.jpg'),
(5,'char.jpg'),(5,'char2.jpg'),
(6,'appart.jpg'),(6,'appart2.jpg');