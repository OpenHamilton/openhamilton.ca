/*  Create Feedback system tables and structure
    By: Peter Santos
*/
DROP TABLE if EXISTS 
    Response, Data_Feedback, FeedbackVote,
    DataRank, FeedbackTag, FeedbackUser,
    FeedbackAnon,
    Feedback, Data, UserOH, FeedbackCat, DataCat;

CREATE TABLE FeedbackCat
(
    ID tinyint unsigned NOT NULL,
    category varchar(50) NOT NULL,
    PRIMARY KEY (ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE DataCat
(
    ID tinyint unsigned NOT NULL,
    category varchar(50) NOT NULL,
    PRIMARY KEY (ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Data
(
    ID smallint unsigned NOT NULL,
    name varchar(100) NOT NULL,
    FK_DataCat_ID tinyint unsigned,
    PRIMARY KEY (ID),
    FOREIGN KEY (FK_DataCat_ID) REFERENCES DataCat(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE UserOH
(
    ID int unsigned NOT NULL AUTO_INCREMENT,
    email varchar(100) NOT NULL,
    pass varchar(100) NOT NULL,
    screen_name varchar(30) NOT NULL,
    PRIMARY KEY (ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Feedback
(
    ID int unsigned NOT NULL AUTO_INCREMENT,
    FK_FeedbackCat_ID tinyint unsigned NOT NULL,
    message text,
    submit_time datetime,
    spam_rank tinyint,
    published boolean NOT NULL,
    PRIMARY KEY (ID),
    FOREIGN KEY (FK_FeedbackCat_ID) REFERENCES FeedbackCat(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Response
(
    FK_Feedback_ID_R int unsigned NOT NULL,
    FK_Feedback_ID int unsigned NOT NULL,
    PRIMARY KEY (FK_Feedback_ID_R),
    FOREIGN KEY (FK_Feedback_ID_R) REFERENCES Feedback(ID),
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE Data_Feedback
(
    FK_Feedback_ID int unsigned NOT NULL,
    FK_Data_ID smallint unsigned NOT NULL,
    PRIMARY KEY (FK_Feedback_ID),
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID),
    FOREIGN KEY (FK_Data_ID) REFERENCES Data(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE FeedbackVote
(
    FK_UserOH_ID int unsigned NOT NULL,
    FK_Feedback_ID int unsigned NOT NULL,
    vote tinyint,
    PRIMARY KEY (FK_UserOH_ID, FK_Feedback_ID),
    FOREIGN KEY (FK_UserOH_ID) REFERENCES UserOH(ID),
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE DataRank
(
    FK_UserOH_ID int unsigned NOT NULL,
    FK_Data_ID smallint unsigned NOT NULL,
    rank tinyint,
    PRIMARY KEY (FK_UserOH_ID, FK_Data_ID),
    FOREIGN KEY (FK_UserOH_ID) REFERENCES UserOH(ID),
    FOREIGN KEY (FK_Data_ID) REFERENCES Data(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;
