drop table if exists polls;
create table polls(
	poll_id varchar(6) primary key not null,
    prompt varchar(256) not null,
    shuffle boolean not null,
    multiple_select boolean not null,
    ip_lock boolean not null
);

drop table if exists queries;
create table queries(
	query_id integer(16) primary key auto_increment,
    poll_id varchar(6) not null,
    query_index integer(2),
    query_text varchar(256) not null
);

drop table if exists responses;
create table responses(
	response_id integer(16) primary key auto_increment,
    poll_id varchar(6) not null,
    pollquery_id integer(16) not null,
    responsevalue boolean not null,
    ip varchar(32)
);