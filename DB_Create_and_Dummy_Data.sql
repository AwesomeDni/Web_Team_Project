##DB 생성
create database web_project default character set= utf8 collate utf8_general_ci ;
use web_project;

##회원 테이블
create table user_tb(
	user_no int primary key auto_increment,
    id varchar(20) not null unique,
    pw varchar(20) not null,
    email varchar(20) not null unique
)default charset = utf8;

##카테고리 테이블
create table category_tb(
	category_no int primary key auto_increment,
    category_nm varchar(20) 
)default charset = utf8;

##게시글 테이블
create table contents_tb(
	content_no int primary key auto_increment,
    title varchar(20) not null,
    content mediumtext,
	user_no int,
    category_no int,
    write_dt datetime,
    view_cnt int default 0,
    foreign key (user_no) references user_tb(user_no) on delete cascade,
    foreign key (category_no) references category_tb(category_no) on delete cascade
)default charset = utf8;

##댓글 테이블
create table coments_tb(
	coment_no int primary key auto_increment,
    coment mediumtext,
    user_no int,
    content_no int,
    write_dt datetime,
    foreign key (user_no) references user_tb(user_no) on delete cascade,
    foreign key (content_no) references contents_tb(content_no) on delete cascade
)default charset = utf8;

##회원 테이블 더미 데이터
insert into user_tb(id,pw,email) values('abc','111','abc@a.com');
insert into user_tb(id,pw,email) values('php','222','php@php.com');
insert into user_tb(id,pw,email) values('java','333','java@java.com');

##카테고리 테이블 더미 데이터
insert into category_tb(category_nm) values('PHP');
insert into category_tb(category_nm) values('JAVA');
insert into category_tb(category_nm) values('PYTHON');
insert into category_tb(category_nm) values('Laravel');
insert into category_tb(category_nm) values('Eclips');

##게시글 테이블 더미 데이터
insert into contents_tb(title,content,user_no,category_no,write_dt) values('test','DOC for test',1,1,now());
insert into contents_tb(title,content,user_no,category_no,write_dt) values('php','php is awesome',2,2,now());
insert into contents_tb(title,content,user_no,category_no,write_dt) values('how','coding is fun',3,1,now());
insert into contents_tb(title,content,user_no,category_no,write_dt) values('testtt','umm..',3,4,now());
insert into contents_tb(title,content,user_no,category_no,write_dt) values('tseses','wow..',2,6,now());

##댓글 테이블 더미 데이터
insert into coments_tb(coment,user_no,content_no,write_dt) values('this too', 2,1,now());
insert into coments_tb(coment,user_no,content_no,write_dt) values('java also', 3,2,now());

create view show_view as select content_no,title,content,id,write_dt,view_cnt from contents_tb,user_tb where contents_tb.user_no=user_tb.user_no;
select * from show_view;

##삭제 테스트
#delete from contents_tb where content_no = 2;
#delete from coments_tb whuser_tbere coment_no = 2;

##DB 인코딩 설정 확인
#SHOW VARIABLES LIKE 'c%';
#set foreign_key_checks =0; #fk로 종속 관계로 삭제 및 수정에 에러 발생시
#drop database web_project;
#drop table user_tb;
#drop table category_tb;
#drop table contents_tb;
#drop table coments_tb;
