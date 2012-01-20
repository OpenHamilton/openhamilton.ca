/*  Create Feedback system tables and structure
    By: Peter Santos
*/
DROP TABLE if EXISTS 
    Response, Data_Feedback, FeedbackVote,
    DataRank, DataTag, FeedbackUser,
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
    FOREIGN KEY (FK_DataCat_ID) 
        REFERENCES DataCat(ID)
            ON UPDATE CASCADE
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
    FOREIGN KEY (FK_FeedbackCat_ID) 
        REFERENCES FeedbackCat(ID)
            ON UPDATE CASCADE
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
    FOREIGN KEY (FK_Data_ID) 
        REFERENCES Data(ID)
            ON UPDATE CASCADE
            ON DELETE CASCADE
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE FeedbackVote
(
    FK_UserOH_ID int unsigned NOT NULL,
    FK_Feedback_ID int unsigned NOT NULL,
    vote tinyint,
    PRIMARY KEY (FK_UserOH_ID, FK_Feedback_ID),
    FOREIGN KEY (FK_UserOH_ID) 
        REFERENCES UserOH(ID)
            ON DELETE CASCADE,
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE DataRank
(
    FK_UserOH_ID int unsigned NOT NULL,
    FK_Data_ID smallint unsigned NOT NULL,
    rank tinyint,
    PRIMARY KEY (FK_UserOH_ID, FK_Data_ID),
    FOREIGN KEY (FK_UserOH_ID) 
        REFERENCES UserOH(ID)
            ON DELETE CASCADE,
    FOREIGN KEY (FK_Data_ID) 
        REFERENCES Data(ID)
            ON UPDATE CASCADE
            ON DELETE CASCADE
) ENGINE InnoDB DEFAULT CHARSET=utf8;

/* implementing this table is optional */
CREATE TABLE DataTag
(
    FK_Data_ID smallint unsigned,
    tags varchar(100),
    PRIMARY KEY (FK_Data_ID),
    FOREIGN KEY (FK_Data_ID) REFERENCES Data(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE FeedbackUser
(
    FK_Feedback_ID int unsigned,
    FK_UserOH_ID int unsigned,
    PRIMARY KEY (FK_Feedback_ID),
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID),
    FOREIGN KEY (FK_UserOH_ID) 
        REFERENCES UserOH(ID)
            ON DELETE CASCADE
) ENGINE InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE FeedbackAnon
(
    FK_Feedback_ID int unsigned,
    screen_name varchar(30),
    PRIMARY KEY (FK_Feedback_ID),
    FOREIGN KEY (FK_Feedback_ID) REFERENCES Feedback(ID)
) ENGINE InnoDB DEFAULT CHARSET=utf8;


/* insert default categories */
INSERT INTO FeedbackCat (ID, category) 
    VALUES (1, 'General Feedback'), (2, 'Question'),
            (3, 'Response'), (4, 'Dataset Request'),
            (5, 'Dataset Feedback'), (6, 'App Request'),
            (7, 'App Feedback');
            
INSERT INTO DataCat (ID, category)
    VALUES (1, 'Dataset'), (2, 'App');


/*
    This section consists of fake data for testing
    purposes.
    NOTE: This can be removed after testing
*/

/* insert fake users */
INSERT INTO UserOH 
        (email, pass, screen_name)
    VALUES 
        ('user1@gmail.com', 'user1', 'user1'),
        ('user2@gmail.com', 'user2', 'user2'),
        ('user3@gmail.com', 'user3', 'user3'),
        ('user4@gmail.com', 'user4', 'user4'),
        ('user5@gmail.com', 'user5', 'user5'),
        ('user6@gmail.com', 'user6', 'user6');

/* insert fake feedback */
INSERT INTO Feedback 
        (FK_FeedbackCat_ID, message, submit_time, spam_rank, published)
    VALUES
        (1, 'test1', NOW(), -2, 1),
        (2, 'response1 to test1', NOW(), -2, 1),
        (1, 'test2', NOW(), -2, 1),
        (2, 'response1 to response1 to test1', NOW(), -2, 1),
        (2, 'response2 to response1 to test1', NOW(), -2, 1),
        (1, 'test3', NOW(), -1, 0),
        (5, 'dataset1 feedback', NOW(), -2, 1),
        (7, 'app1 feedback', NOW(), -2, 1),
        (5, 'dataset2 feedback', NOW(), -2, 1);

/* insert fake dataset and app names */
INSERT INTO Data
        (ID, name, FK_DataCat_ID)
    VALUES
        (1, 'HammerBusSMS', 2),
        (2, 'Dowsing', 2),
        (3, 'Election Results', 1),
        (4, 'Useless Dataset', 1);
                
/* insert Data_Feedback relationships */
INSERT INTO Data_Feedback
        (FK_Feedback_ID, FK_Data_ID)
    VALUES
        (8, 1),
        (7, 3),
        (9, 4);

/* insert Response relationships */
INSERT INTO Response
        (FK_Feedback_ID_R, FK_Feedback_ID)
    VALUES
        (2, 1),
        (4, 2),
        (5, 2);
        
/* insert FeedbackVote relationships (vote -1 or 1) */
INSERT INTO FeedbackVote
        (FK_UserOH_ID, FK_Feedback_ID, vote)
    VALUES
        (1, 1, -1),
        (1, 4, 1),
        (3, 7, 1),
        (3, 8, 1);

/* insert DataRank relationships (rank from 1 to 4)*/
INSERT INTO DataRank
        (FK_UserOH_ID, FK_Data_ID, rank)
    VALUES
        (3, 1, 4),
        (4, 1, 4),
        (5, 1, 3),
        (6, 2, 2),
        (3, 2, 4),
        (4, 2, 3),
        (5, 2, 2),
        (5, 3, 3),
        (6, 3, 1);

/* insert FeedbackTag relationships */
INSERT INTO DataTag
        (FK_Data_ID, tags)
    VALUES
        (1, 'Transit, HSR'),
        (2, 'Pools, Swimming, Summer'),
        (3, 'Politics');
        
/* insert FeedbackUser relationships */
INSERT INTO FeedbackUser
        (FK_Feedback_ID, FK_UserOH_ID)
    VALUES
        (1, 3),
        (4, 2),
        (7, 1);

/* insert FeedbackAnon relationships */
INSERT INTO FeedbackAnon
        (FK_Feedback_ID, screen_name)
    VALUES
        (2, ''),
        (3, 'Smith44'),
        (5, 'SpruceWillis'),
        (6, 'Tom_Cruise00'),
        (8, ''),
        (9, 'daisy34');
        

