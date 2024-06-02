CREATE DATABASE AlumniClub;

CREATE TABLE Users (
    UserId          INT      NOT NULL,
    FirstName       VARCHAR(30) NOT NULL,
    LastName        VARCHAR(30) NOT NULL,
	EmailAddress    VARCHAR(30) NOT NULL,
	UserPassword    VARCHAR(30) NOT NULL,
    PhoneNumber     VARCHAR(30) NOT NULL,
    UserType        VARCHAR(30) NOT NULL,
     
    CONSTRAINT User_PK  PRIMARY KEY (UserId),
    CONSTRAINT User_AK3 UNIQUE (EmailAddress)
);

CREATE TABLE Major (
    MajorId         INT      NOT NULL,
    MajorName            CHAR(30) NOT NULL,  
    -- 
    CONSTRAINT Major_PK PRIMARY KEY (MajorId),
    CONSTRAINT Major_AK UNIQUE      (MajorName)   
);

CREATE TABLE Graduate (
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

CREATE TABLE Recruiter (
    RecruiterId      INT         NOT NULL,
    CompanyName      VARCHAR(50) NOT NULL,  

    CONSTRAINT Recruiter_PK         PRIMARY KEY (RecruiterId),
    CONSTRAINT RecruiterToUser_FK  FOREIGN KEY (RecruiterId)
        REFERENCES Users (UserId)   
);

CREATE TABLE EventInfo  (
	EventId         INT NOT NULL,
    EventName       VARCHAR(30)  NOT NULL,
    EventDesc       VARCHAR(200) NOT NULL,  
    CreatedEventDateTime DATETIME NOT NULL,

    CONSTRAINT Country_PK PRIMARY KEY (EventId)
	   
);

CREATE TABLE UECreated (
	UECreatedId INT NOT NULL,
	UserId INT NOT NULL,
	EventId INT NOT NULL,

	CONSTRAINT UECreated_PK        PRIMARY KEY (UECreatedId),
    CONSTRAINT UECreatedToUser_FK  FOREIGN KEY (UserId)
        REFERENCES Users (UserId),
	CONSTRAINT UECreatedToEvent_FK  FOREIGN KEY (EventId)
        REFERENCES EventInfo (EventId)
);

CREATE TABLE UEAccepted (
	UEAcceptedId INT NOT NULL,
	UserId     INT NOT NULL,
	EventId    INT NOT NULL,

	CONSTRAINT UEAccepted_PK        PRIMARY KEY (UEAcceptedId),
    CONSTRAINT UEAcceptedToUser_FK  FOREIGN KEY (UserId)
        REFERENCES Users (UserId),
	CONSTRAINT UEAcceptedToEvent_FK  FOREIGN KEY (EventId)
        REFERENCES EventInfo (EventId)
);

CREATE TABLE MessagesInfo (
	MessagesInfoId INT NOT NULL,
	SenderId       INT NOT NULL,
	RecipientId    INT NOT NULL,
	MessageText    VARCHAR(100) NOT NULL,
	
	CONSTRAINT MessagesInfo_PK        PRIMARY KEY (MessagesInfoId),
    CONSTRAINT MessagesInfoToSender_FK  FOREIGN KEY (SenderId)
        REFERENCES Users (UserId),
	CONSTRAINT MessagesInfoToRecipient_FK  FOREIGN KEY (RecipientId)
        REFERENCES Users (UserId)
);

CREATE TABLE AdInfo (
	AdId              INT NOT NULL,
	RecruiterId       INT NOT NULL,
	AdName            VARCHAR(50) NOT NULL,
	AdDesc            VARCHAR(200) NOT NULL,
    CreatedEventDateTime DATETIME NOT NULL,
	
	CONSTRAINT AdInfo_PK        PRIMARY KEY (AdId),
    CONSTRAINT AdInfoToRecruiter_FK  FOREIGN KEY (RecruiterId)
        REFERENCES Users (UserId)
);

