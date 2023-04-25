DROP TABLE Mascot CASCADE CONSTRAINTS;
DROP TABLE Country CASCADE CONSTRAINTS;
DROP TABLE City CASCADE CONSTRAINTS;
DROP TABLE Competition CASCADE CONSTRAINTS;
DROP TABLE CompetitionSport CASCADE CONSTRAINTS;
DROP TABLE Competes_In CASCADE CONSTRAINTS;
DROP TABLE Person CASCADE CONSTRAINTS;
DROP TABLE Athlete CASCADE CONSTRAINTS;
DROP TABLE Coach CASCADE CONSTRAINTS;
DROP TABLE Represents CASCADE CONSTRAINTS;
DROP TABLE Sport CASCADE CONSTRAINTS;
DROP TABLE Team CASCADE CONSTRAINTS;
DROP TABLE SportingEvent CASCADE CONSTRAINTS;
DROP TABLE AgeCategory CASCADE CONSTRAINTS;
DROP TABLE Venue CASCADE CONSTRAINTS;
DROP TABLE PostalCodeCity CASCADE CONSTRAINTS;
DROP TABLE Coaches CASCADE CONSTRAINTS;
DROP TABLE Member_Of CASCADE CONSTRAINTS;
DROP TABLE WeightClass CASCADE CONSTRAINTS;

-- create tables 
CREATE TABLE WeightClass (
    mass INTEGER,
    weightClass CHAR(50),
    PRIMARY KEY (mass)
);

CREATE TABLE Venue (
    venueName CHAR(50) PRIMARY KEY,
    capacity INTEGER,
    postalCode CHAR(50)
);

CREATE TABLE AgeCategory (
    age INTEGER,
    ageCategory CHAR(25) NOT NULL,
    PRIMARY KEY (age)
);


CREATE TABLE Sport (
    sportName CHAR(60),
    PRIMARY KEY (sportName)
);

CREATE TABLE Person (
    participantID INTEGER,
    firstName CHAR(60) NOT NULL,
    lastName CHAR(60) NOT NULL,
    age INTEGER NOT NULL,
    PRIMARY KEY (participantID)
);

CREATE TABLE Mascot (
    mascotName CHAR(50),
    mascotType CHAR(50) NOT NULL,
    PRIMARY KEY (mascotName)
);

CREATE TABLE Country (
    countryName CHAR(100),
    PRIMARY KEY (countryName)
);


CREATE TABLE City (
    latitude DECIMAL,
    longitude DECIMAL,
    cityName CHAR(100) NOT NULL,
    countryName CHAR(100) NOT NULL,
    PRIMARY KEY (latitude, longitude),
    FOREIGN KEY (countryName) REFERENCES Country,
    UNIQUE (countryName)
);


CREATE TABLE Competition (
    competitionName CHAR(100),
    yearVal INTEGER,
    budget INTEGER,
    latitude DECIMAL NOT NULL,
    longitude DECIMAL NOT NULL,
    PRIMARY KEY (competitionName, yearVal),
    FOREIGN KEY (latitude, longitude) REFERENCES City
);

CREATE TABLE CompetitionSport (
    competitionName CHAR(100),
    yearVal INTEGER,
    sportName CHAR(60),
    PRIMARY KEY (competitionName, yearVal, sportName),
    FOREIGN KEY (competitionName, yearVal) REFERENCES Competition(competitionName, yearVal),
    FOREIGN KEY (sportName) REFERENCES Sport
);

CREATE TABLE SportingEvent (
    sportName CHAR(60),
    eventName CHAR(60),
    startDate DATE,
    endDate DATE,
    venueName CHAR(50) NOT NULL,
    competitionName CHAR(100),
    competitionYear INTEGER,
    PRIMARY KEY (sportName, eventName, competitionName, competitionYear),
    FOREIGN KEY (sportName) REFERENCES Sport(sportName) ON DELETE CASCADE,
    FOREIGN KEY (venueName) REFERENCES Venue,
    FOREIGN KEY (competitionName, competitionYear) REFERENCES Competition ON DELETE CASCADE
);

CREATE TABLE Competes_In (
    participantID INTEGER,
    eventName CHAR(60),
    sportName CHAR(60),
    ranking INTEGER,
    competitionName CHAR(100),
    competitionYear INTEGER,
    PRIMARY KEY (participantID, eventName, sportName, competitionName, competitionYear),
    FOREIGN KEY (participantID) REFERENCES Person(participantID) ON DELETE CASCADE,
    FOREIGN KEY (eventName, sportName, competitionName, competitionYear) REFERENCES SportingEvent(eventName, sportName, competitionName, competitionYear)
);

CREATE TABLE Athlete (
    participantID INTEGER,
    height INTEGER,
    mass INTEGER,
    PRIMARY KEY (participantID),
    FOREIGN KEY (participantID) REFERENCES Person ON DELETE CASCADE
);

CREATE TABLE Coach (
    participantID INTEGER,
    experience INTEGER,
    PRIMARY KEY (participantID),
    FOREIGN KEY (participantID) REFERENCES Person ON DELETE CASCADE
);


CREATE TABLE Represents (
    mascotName CHAR(50),
    competitionName CHAR(100),
    yearVal INTEGER,
    PRIMARY KEY (mascotName, competitionName, yearVal),
    FOREIGN KEY (mascotName) REFERENCES Mascot,
    FOREIGN KEY (competitionName, yearVal) REFERENCES Competition(competitionName, yearVal)
);

CREATE TABLE Team (
    teamID CHAR(10),
    capacity INTEGER,
    teamName CHAR(50),
    established INTEGER,
    sportName CHAR(60),
    countryName CHAR(100)                                                                                                                                                                    ,
    PRIMARY KEY (teamID),
    FOREIGN KEY (sportName) REFERENCES Sport,
    FOREIGN KEY (countryName) REFERENCES Country
);

CREATE TABLE PostalCodeCity (
    postalCode CHAR(50) PRIMARY KEY,
    latitude  DECIMAL,
    longitude DECIMAL,
    countryName CHAR(100),
    FOREIGN KEY (latitude, longitude) REFERENCES City,
    FOREIGN KEY (countryName) REFERENCES Country
);

CREATE TABLE Coaches (
    athleteID INTEGER,
    coachID INTEGER,
    startDate DATE,
    PRIMARY KEY (athleteID, coachID),
    FOREIGN KEY (athleteID) REFERENCES Athlete ON DELETE CASCADE,
    FOREIGN KEY (coachID) REFERENCES Coach ON DELETE CASCADE
);

CREATE TABLE Member_Of (
    participantID INTEGER,
    teamID CHAR(10),
    startDate date,
    PRIMARY KEY (participantID, teamID),
    FOREIGN KEY (participantID) REFERENCES Person ON DELETE CASCADE,
    FOREIGN KEY (teamID) REFERENCES Team
);

-- populate tables

INSERT INTO Mascot(mascotName, mascotType) VALUES ('Thunder', 'Thunderbird');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Quatchi', 'sasquatch');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Miga', 'killer whale');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Sumi', 'Thunderbird');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Thundy', 'Thunderbird');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Vinicius', 'Brazilian hybrid animal');
INSERT INTO Mascot(mascotName, mascotType) VALUES ('Wenlock', 'Brazilian plant');


INSERT INTO Country(countryName) VALUES ('Canada');
INSERT INTO Country(countryName) VALUES ('Japan');
INSERT INTO Country(countryName) VALUES ('France');
INSERT INTO Country(countryName) VALUES ('Morocco');
INSERT INTO Country(countryName) VALUES ('Australia');
INSERT INTO Country(countryName) VALUES ('United Kingdom');
INSERT INTO Country(countryName) VALUES ('United States');
INSERT INTO Country(countryName) VALUES ('India');
INSERT INTO Country(countryName) VALUES ('Spain');
INSERT INTO Country(countryName) VALUES ('New Zealand');

INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (51.5074, 0.1278, 'London', 'United Kingdom');
INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (40.7128, 74.0060, 'New York City', 'United States');
INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (35.6895, 139.6917, 'Tokyo', 'Japan');
INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (48.8566, 2.3522, 'Paris', 'France');
INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (37.7749, 122.4194, 'San Francisco', 'Morocco');
INSERT INTO City(latitude, longitude, cityName, countryName) VALUES (25.2744, 133.7751, 'Sydney', 'Australia');

INSERT INTO Sport(sportName) VALUES ('Basketball');
INSERT INTO Sport(sportName) VALUES ('Ice hockey');
INSERT INTO Sport(sportName) VALUES ('Figure Skating');
INSERT INTO Sport(sportName) VALUES ('Skiing');
INSERT INTO Sport(sportName) VALUES ('Soccer');
INSERT INTO Sport(sportName) VALUES ('Rugby');
INSERT INTO Sport(sportName) VALUES ('Football');
INSERT INTO Sport(sportName) VALUES ('Tennis');
INSERT INTO Sport(sportName) VALUES ('Cycling');
INSERT INTO Sport(sportName) VALUES ('American Football');
INSERT INTO Sport(sportName) VALUES ('Cricket');

INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('FIFA World Cup Qatar 2022', 2022, 1, 35.6895,139.6917);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Australian Open 2023', 2023, NULL,  25.2744, 133.7751);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Tour de France 2022', 2022, NULL, 48.8566, 2.3522);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Super Bowl LVI', 2022, NULL,40.7128, 74.0060);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Winter Youth Olympics', 2020, NULL, 51.5074, 0.1278);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Winter Youth Olympics', 2010, NULL, 51.5074, 0.1278);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Winter Olympics', 2010,  NULL, 37.7749, 122.4194);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Winter Olympics', 2020,  NULL, 37.7749, 122.4194);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Summer Olympics', 2012, 300, 40.7128, 74.0060);
INSERT INTO Competition(competitionName, yearVal, budget, latitude, longitude) VALUES ('Summer Olympics', 2016, 300, 40.7128, 74.0060);

INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('FIFA World Cup Qatar 2022', 2022,'Football');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Australian Open 2023', 2023, 'Tennis');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Tour de France 2022', 2022, 'Cycling');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Super Bowl LVI', 2022, 'American Football');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Winter Olympics', 2010, 'Skiing');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Summer Olympics', 2012, 'Tennis');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Summer Olympics', 2016, 'Tennis');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Winter Youth Olympics', 2010,'Skiing');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Winter Youth Olympics', 2020,'Skiing');
INSERT INTO CompetitionSport(competitionName, yearVal, sportName) VALUES ('Winter Youth Olympics', 2020, 'Figure Skating');

INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Wembley Stadium', 90000, 'HA90WS');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Madison Square Garden', 20789, 'NY 10121');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Melbourne Cricket Ground', 100024, 'VIC 3002');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Estadio Azteca', 87523, '04480');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Staples Center', NULL, 'CA 90015');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('All England Lawn Tennis and Croquet Club', 1111, 'CA 90015');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Gangneung Ice Arena', 1111, 'CA 90015');
INSERT INTO Venue(venueName, capacity, postalCode) VALUES ('Yongpyong Alpine Centre', 10000, 'CA 90015');

INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Tennis', 'Women''s Doubles', Date'2012-06-26', Date'2012-07-09', 'All England Lawn Tennis and Croquet Club', 'Summer Olympics', 2012);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Tennis', 'Women''s Single', Date'2012-06-26', Date'2012-07-09', 'All England Lawn Tennis and Croquet Club', 'Summer Olympics', 2012);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Figure Skating', 'Singles Skating', Date'2020-01-15', Date'2020-01-16', 'Gangneung Ice Arena', 'Winter Youth Olympics', 2020);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Tennis', 'Men''s Double', Date'2012-06-26', Date'2012-07-09', 'All England Lawn Tennis and Croquet Club', 'Summer Olympics', 2016);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Figure Skating', 'Ice Dance', Date'2020-01-15', Date'2020-01-16', 'Gangneung Ice Arena', 'Winter Olympics', 2020);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Figure Skating', 'Singles Skating', Date'2020-02-15', Date'2020-02-26', 'Gangneung Ice Arena', 'Winter Olympics', 2020);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Skiing', 'Slalom', Date'2010-02-19', Date'2010-02-19', 'Yongpyong Alpine Centre', 'Winter Youth Olympics', 2010);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Skiing', 'Slalom', Date'2020-02-19', Date'2020-02-19', 'Yongpyong Alpine Centre', 'Winter Youth Olympics', 2020);
INSERT INTO SportingEvent(sportName, eventName, startDate, endDate, venueName, competitionName, competitionYear) VALUES ('Figure Skating', 'Singles Skating', Date'2010-02-15', Date'2010-02-26', 'Gangneung Ice Arena', 'Winter Youth Olympics', 2010);

INSERT INTO Person(participantID, firstName, lastName, age) VALUES (1, 'Ryan', 'Gao', 19);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (2, 'Edward', 'Chong', 20);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (3, 'Julia', 'You', 20);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (4, 'Jessica', 'Wong', 100);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (5, 'Jason', 'Hall', 15);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (123, 'Greg', 'Patel', 65);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (12345, 'Brad', 'Nguyen', 42);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (6, 'Jed', 'Garcia', 45);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (99, 'Chris', 'Martin', 31);
INSERT INTO Person(participantID, firstName, lastName, age) VALUES (99999, 'Ryan', 'Lee', 60);

INSERT INTO Athlete(participantID, height, mass) VALUES (1, NULL, NULL);
INSERT INTO Athlete(participantID, height, mass) VALUES (2, 100, 91);
INSERT INTO Athlete(participantID, height, mass) VALUES (3, NULL, 57);
INSERT INTO Athlete(participantID, height, mass) VALUES (4, NULL, 67);
INSERT INTO Athlete(participantID, height, mass) VALUES (5, 200, 73);

INSERT INTO WeightClass(mass, weightClass) VALUES (51, 'flyweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (52, 'flyweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (57, 'featherweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (67, 'welterweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (68, 'welterweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (73, 'middleweight');
INSERT INTO WeightClass(mass, weightClass) VALUES (91, 'cruiserweight');

INSERT INTO Coach(participantID, experience) VALUES (123, 10);
INSERT INTO Coach(participantID, experience) VALUES (12345, 5);
INSERT INTO Coach(participantID, experience) VALUES (6, 20);
INSERT INTO Coach(participantID, experience) VALUES (99, 0);
INSERT INTO Coach(participantID, experience) VALUES (99999, 33);

INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (1, 'Women''s Doubles', 'Tennis', 10, 'Summer Olympics', 2012);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (2, 'Women''s Single', 'Tennis', 1, 'Summer Olympics', 2012);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (3, 'Men''s Double', 'Tennis', NULL, 'Summer Olympics', 2016);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (4, 'Slalom', 'Skiing', 1, 'Winter Youth Olympics', 2010);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (4, 'Singles Skating', 'Figure Skating', 2, 'Winter Youth Olympics', 2010);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (5, 'Singles Skating', 'Figure Skating', 2, 'Winter Youth Olympics', 2020);
INSERT INTO Competes_In(participantID, eventName, sportName, ranking, competitionName, competitionYear) VALUES (5, 'Slalom', 'Skiing', 1, 'Winter Youth Olympics', 2020);

INSERT INTO AgeCategory(age, ageCategory) VALUES (14, 'U16');
INSERT INTO AgeCategory(age, ageCategory) VALUES (15, 'U16');
INSERT INTO AgeCategory(age, ageCategory) VALUES (17, 'U20');
INSERT INTO AgeCategory(age, ageCategory) VALUES (18, 'U20');
INSERT INTO AgeCategory(age, ageCategory) VALUES (20, 'U20');
INSERT INTO AgeCategory(age, ageCategory) VALUES (100, 'Senior');
INSERT INTO AgeCategory(age, ageCategory) VALUES (200, 'Senior');
INSERT INTO AgeCategory(age, ageCategory) VALUES (150, 'Senior');

INSERT INTO PostalCodeCity(postalCode, latitude, longitude, countryName) VALUES ('HA90WS', 51.5074, 0.1278, 'United Kingdom');
INSERT INTO PostalCodeCity(postalCode, latitude, longitude, countryName) VALUES ('NY 10121', 40.7128, 74.0060, 'United States');
INSERT INTO PostalCodeCity(postalCode, latitude, longitude, countryName) VALUES ('VIC 3002', 35.6895, 139.6917, 'Australia');
INSERT INTO PostalCodeCity(postalCode, latitude, longitude, countryName) VALUES ('04480', 48.8566, 2.3522, 'India');
INSERT INTO PostalCodeCity(postalCode, latitude, longitude, countryName) VALUES ('CA 90015', 37.7749, 122.4194, 'United States');

INSERT INTO Coaches(athleteID, coachID, startDate) VALUES (1, 123, Date'2022-02-05');
INSERT INTO Coaches(athleteID, coachID, startDate) VALUES (2, 12345, Date'2023-01-12');
INSERT INTO Coaches(athleteID, coachID, startDate) VALUES (3, 6, Date'2021-05-26');
INSERT INTO Coaches(athleteID, coachID, startDate) VALUES (4, 99, Date'2023-01-12');
INSERT INTO Coaches(athleteID, coachID, startDate) VALUES (5, 99999, Date'2020-01-01');

INSERT INTO Represents(mascotName, competitionName, yearVal) VALUES ('Miga', 'Winter Youth Olympics', 2020);
INSERT INTO Represents(mascotName, competitionName, yearVal) VALUES ('Quatchi', 'Winter Olympics', 2010);
INSERT INTO Represents(mascotName, competitionName, yearVal) VALUES ('Sumi', 'Winter Olympics', 2010);
INSERT INTO Represents(mascotName, competitionName, yearVal) VALUES ('Wenlock', 'Summer Olympics', 2012);
INSERT INTO Represents(mascotName, competitionName, yearVal) VALUES ('Vinicius', 'Summer Olympics', 2016);

INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName) VALUES ('MUFC001', 74140, 'Manchester United Football Club', 1878, 'Football', 'United Kingdom');
INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName) VALUES ('LAL001', 18997, 'Los Angeles Lakers', 1947, 'Basketball', 'United States');
INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName) VALUES ('NZAB001', 50000, 'New Zealand National Rugby Union Team', 1884, 'Rugby', 'New Zealand');
INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName) VALUES ('RMD001', 81044, 'Real Madrid Club de Futbol', 1902, 'Football', 'Spain');
INSERT INTO Team(teamID, capacity, teamName, established, sportName, countryName) VALUES ('MI001', 33108, 'Mumbai Indians', 2008, 'Cricket', 'India');

INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (1, 'MUFC001', Date'2010-02-05');
INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (123, 'LAL001', Date'2010-02-05');
INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (1, 'LAL001', Date'2010-02-05');
INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (2, 'MUFC001', Date'2010-02-05');
INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (3, 'MUFC001', Date'2010-02-05');
INSERT INTO Member_Of(participantID, teamID, startDate) VALUES (1, 'NZAB001', Date'2012-02-05');