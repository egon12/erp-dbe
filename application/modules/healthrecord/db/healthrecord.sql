drop table healthrecord_general;

drop table healthrecord_observation;

drop table healthrecord_disease;

create table healthrecord_general (
  id int primary key auto_increment,
  customer_id int not null,
  systolic smallint unsigned,
  diastolic smallint unsigned,
  blood_glucose smallint unsigned,
  uric_acid smallint unsigned,
  user_id int,
  timestamp timestamp,
  deleted smallint default 0
);

create table healthrecord_observation (
  id int primary key auto_increment,
  customer_id int not null,
  notes text,
  user_id int,
  timestamp timestamp,
  deleted smallint default 0
);

create table healthrecord_disease (
  id int primary key auto_increment,
  customer_id int not null,
  picture blob,
  user_id int,
  timestamp timestamp,
  deleted smallint default 0
);
