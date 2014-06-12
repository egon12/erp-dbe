drop table flow_session;

drop table flow_history;

create table flow_session (
    id int primary key auto_increment,
    customer_id int,
    data text,
    flow_order int,
    timestamp timestamp
);

create table flow_history (
    id int primary key auto_increment,
    customer_id int,
    data text,
    flow_order int,
    timestamp timestamp
);
