/***
 * BASE DE DATOS DE PROPOSITO GENERAL
 * 
 * 0000   0   0  0  0     0    0   00  000    0000  0   0000 
 * 0      0   0  0  0     0 0  0  0  0 0  0  0      0  0 
 * 000    0   0  0  0     0 0  0  0  0 0  0   0000  0   0000  
 * 0       0 0   0  0     0  0 0  0000 000       0  0      0
 * 0000     0    0  0000  0    0  0  0 0     0000   0  0000
 * https://evilnapsis.com/
 * **/
create database cotizalite;
use cotizalite;

create table user(
	id int not null auto_increment primary key,
	name varchar(50),
	lastname varchar(50),
	username varchar(50),
	email varchar(255),
	password varchar(60),
	image varchar(255),
	status int default 1,
	kind int default 1,
	created_at datetime
);

/**
* password: encrypted using sha1(md5("mypassword"))
* status: 1. active, 2. inactive, 3. other, ...
* kind: 1. root, 2. other, ...
**/

/* insert user example */
insert into user (name,lastname, email,password,created_at) value ("Administrator","","admin",sha1(md5("admin")),NOW());


create table city(
	id int not null auto_increment primary key,
	name varchar(50)
);

insert into city (name) value ("Villahermosa");

create table credit(
	id int not null auto_increment primary key,
	name varchar(50)
);

insert into credit (name) value ("Normal");

create table category(
	id int not null auto_increment primary key,
	name varchar(50)
);

insert into category (name) value ("Categoria 1");


create table status(
	id int not null auto_increment primary key,
	name varchar(50)
);

create table forma(
	id int not null auto_increment primary key,
	name varchar(50)
);

insert into forma (name) value ("Efectivo");


insert into status (id, name) value (1,"Borrador");
insert into status (id, name) value (2,"Pendiente");
insert into status (id, name) value (3,"Vendido");


create table person(
	id int not null auto_increment primary key,
	code varchar(50),
	rfc varchar(50),
	name varchar(50),
	lastname varchar(50),
	email varchar(255),
	address varchar(255),
	phone varchar(255),
	contact varchar(50),
	comments varchar(50),
	image varchar(255),
	city_id int,
	credit_id int,
	foreign key (city_id) references city(id),
	foreign key (credit_id) references credit(id),
	created_at datetime
);

create table service(
	id int not null auto_increment primary key,
	code varchar(50),
	name varchar(50),
	kind varchar(50),
	amount double,
	category_id int,
	service_id int,
	foreign key (category_id) references category(id),
	foreign key (service_id) references service(id)
);


create table setting(
	id int not null auto_increment primary key,
	name varchar(100) not null unique,
	label varchar(200) not null,
	kind int,
	val text,
	cfg_id int default 1
);

insert into setting(name,label,kind,val) value ("general_logo","Logo",4,"");
insert into setting(name,label,kind,val) value ("general_company","Nombre de la Empresa",1,"");
insert into setting(name,label,kind,val) value ("general_address","Direccion de la Empresa",1,"");
insert into setting(name,label,kind,val) value ("general_email","Email de Contacto",1,"");
insert into setting(name,label,kind,val) value ("general_phone","Telefono",1,"");
insert into setting(name,label,kind,val) value ("general_rfc","RFC",1,"");


create table cotization(
	id int not null auto_increment primary key,
	code varchar(50),
	notes varchar(500),
	total double,
	discount double,
	person_id int,
	user_id int not null,
	status_id int not null,
	forma_id int,
	created_at datetime,
	foreign key (forma_id) references forma(id),
	foreign key (person_id) references person(id),
	foreign key (user_id) references user(id)
);

create table cotization_item(
	id int not null auto_increment primary key,
	cotization_id int not null,
	q double,
	discount double,
	service_id int not null,
	foreign key (service_id) references service(id),
	foreign key (cotization_id) references cotization(id)

);