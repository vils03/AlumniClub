CREATE DATABASE alumniclub;

CREATE TABLE alumniclub.Users (
    UserId          INT      NOT NULL AUTO_INCREMENT,
    FirstName       VARCHAR(30) NOT NULL,
    LastName        VARCHAR(30) NOT NULL,
	EmailAddress    VARCHAR(30) NOT NULL,
	UserPassword    VARCHAR(256) NOT NULL,
    PhoneNumber     VARCHAR(30) NOT NULL,
    UserType        VARCHAR(30) NOT NULL,
    UserImage       VARCHAR(50) NOT NULL,
     
    CONSTRAINT User_PK  PRIMARY KEY (UserId),
    CONSTRAINT User_AK3 UNIQUE (EmailAddress)
);

CREATE TABLE alumniclub.Major (
    MajorId         INT      NOT NULL AUTO_INCREMENT,
    MajorName            CHAR(30) NOT NULL,  
     
    CONSTRAINT Major_PK PRIMARY KEY (MajorId),
    CONSTRAINT Major_AK UNIQUE      (MajorName)   
);

CREATE TABLE alumniclub.Graduate (
    GraduateId         INT      NOT NULL,
    FN                 VARCHAR(30) NOT NULL,
	Major              VARCHAR(30) NOT NULL,
	Class              INT      NOT NULL,
	Status             BIT NOT NULL,
	Location           VARCHAR(100) NOT NULL,
    MajorId            INT      NOT NULL,

    CONSTRAINT Graduate_PK            PRIMARY KEY (GraduateId),
    CONSTRAINT GraduateToUser_FK      FOREIGN KEY (GraduateId)
        REFERENCES Users (UserId),
    CONSTRAINT GraduateToMajor_FK FOREIGN KEY (MajorId)
        REFERENCES Major (MajorId)   
);

CREATE TABLE alumniclub.Recruiter (
    RecruiterId      INT         NOT NULL,
    CompanyName      VARCHAR(50) NOT NULL,  

    CONSTRAINT Recruiter_PK         PRIMARY KEY (RecruiterId),
    CONSTRAINT RecruiterToUser_FK  FOREIGN KEY (RecruiterId)
        REFERENCES Users (UserId)   
);

CREATE TABLE alumniclub.EventInfo  (
	EventId         INT NOT NULL AUTO_INCREMENT,
    EventName       VARCHAR(30)  NOT NULL,
    EventDesc       VARCHAR(200) NOT NULL,  
    CreatedEventDateTime DATETIME NOT NULL,
    EventImage      VARCHAR(50) NOT NULL,

    CONSTRAINT Country_PK PRIMARY KEY (EventId)
	   
);

CREATE TABLE alumniclub.UserToEvent (
	UserToEventId INT NOT NULL AUTO_INCREMENT,
	UserId INT NOT NULL,
	EventId INT NOT NULL,
    Accepted BIT,
    Created BIT,

	CONSTRAINT UserToEvent_PK        PRIMARY KEY (UserToEventId),
    CONSTRAINT UEToUser_FK  FOREIGN KEY (UserId)
        REFERENCES Users (UserId),
	CONSTRAINT UEToEvent_FK  FOREIGN KEY (EventId)
        REFERENCES EventInfo (EventId)
);

CREATE TABLE alumniclub.AdInfo (
	AdId              INT NOT NULL AUTO_INCREMENT,
	RecruiterId       INT NOT NULL,
	AdName            VARCHAR(50) NOT NULL,
	AdDesc            VARCHAR(200) NOT NULL,
    CreatedEventDateTime DATETIME NOT NULL,
	
	CONSTRAINT AdInfo_PK        PRIMARY KEY (AdId),
    CONSTRAINT AdInfoToRecruiter_FK  FOREIGN KEY (RecruiterId)
        REFERENCES Recruiter (RecruiterId)
);


INSERT INTO alumniclub.major(MajorName) 
VALUES ("SI"), ("I"), ("IS"), ("KN"), 
("M"), ("PM"), ("AD"), ("MI");
