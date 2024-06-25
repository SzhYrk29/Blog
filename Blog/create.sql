USE Blog_carriednor;

create table Roles (
    role_ID int primary key auto_increment,
    role varchar(32) not null
);

create table Users (
    user_ID int primary key auto_increment,
    username varchar(64) not null,
    email varchar(64) not null,
    password varchar(255) not null,
    role_ID int not null
);

create table Comments (
    comment_ID int primary key auto_increment,
    content text not null,
    publish_time datetime not null,
    username varchar(32),
    user_ID int,
    post_ID int not null
);

create table Posts (
    post_ID int primary key auto_increment,
    title text not null,
    content text not null,
    photo longblob,
    publish_time datetime not null,
    comment_ID int
);

alter table Users add foreign key (role_ID) references Roles(role_ID) on delete cascade;
alter table Comments add foreign key (user_ID) references Users(user_ID) on delete cascade;
alter table Comments add foreign key (post_ID) references Posts(post_ID) on delete cascade;
alter table Posts add foreign key (comment_ID) references Comments(comment_ID) on delete cascade;

insert into Roles (role) values ('administrator'),
                                ('author'),
                                ('user'),
                                ('guest');