
drop table healthrecord_amnanesa;

drop table healthrecord_general;

drop table healthrecord_sugestion;

drop table healthrecord_sugestion_list;

drop table healthrecord_disease;

create table healthrecord_general (
  id int primary key auto_increment,
  customer_id int not null,
  date date,
  amnanesa text,
  diagnostic varchar(256),
  systolic smallint unsigned,
  diastolic smallint unsigned,
  kolestrol smallint unsigned,
  guladarah_puasa smallint unsigned,
  guladarah_sewaktu smallint unsigned,
  guladarah_sesudah smallint unsigned,
  asam_urat decimal(3,2),
  sugestion text,
  user_id int,
  timestamp timestamp
);

create table healthrecord_disease (
  id int primary key auto_increment,
  customer_id int not null,
  picture blob,
  user_id int,
  timestamp timestamp,
  deleted smallint default 0
);

create table healthrecord_sugestion (
  id int primary key auto_increment,
  customer_id int not null,
  sugestion text,
  user_id int,
  timestamp timestamp,
);

create table healthrecord_sugestion_list (
  id int primary key auto_increment,
  diagnostic varchar(512),
  number_therapy varchar(512),
  electrostatic varchar(512),
  biowater varchar(512),
  sauna varchar(512),
  massage varchar(512),
  others varchar(512),
  timestamp timestamp
);

/**

create table healthrecord_general (

  id int primary key auto_increment,
  customer_id int not null,
  amnanesa text,
  diagnostic varchar(256),
  systolic smallint unsigned,
  diastolic smallint unsigned,
  ldl smallint unsigned,
  blood_glucose smallint unsigned,
  blood_glucose_fasting smallint unsigned,
  blood_glucose_before smallint unsigned,
  blood_glucose_after smallint unsigned,
  uric_acid decimal(3,2),
  user_id int,
  timestamp timestamp,
  deleted smallint default 0

  id int primary key auto_increment,
  customer_id int not null,
  amnanesa text,
  diagnostic varchar(256),
  user_id int,
  timestamp timestamp,
  deleted smallint default 0
);

