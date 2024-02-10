CREATE DATABASE IF NOT EXISTS lbeaudin;

USE lbeaudin;

-- Table pour sauvegarder les utilisateurs qui utilise le site.
CREATE TABLE IF NOT EXISTS Users
(
    Id             INT AUTO_INCREMENT PRIMARY KEY,

    Username       VARCHAR(255) NOT NULL,
    HashedPassword VARCHAR(255) NOT NULL,
    Email          VARCHAR(255) NOT NULL,
    FirstName      VARCHAR(255) NOT NULL,
    LastName       VARCHAR(255) NOT NULL,
    UserRole       ENUM ('master', 'default') DEFAULT 'default',
    PhoneNumber    VARCHAR(255) NOT NULL,
    RegisterDate   TIMESTAMP                  DEFAULT CURRENT_TIMESTAMP
);

-- Table pour sauvegarder les clients ajoutés au site.
CREATE TABLE IF NOT EXISTS Customers
(
    Id           INT AUTO_INCREMENT PRIMARY KEY,

    FirstName    VARCHAR(255) NOT NULL,
    LastName     VARCHAR(255) NOT NULL,
    HomePhone    VARCHAR(255) NOT NULL,
    CellPhone    VARCHAR(255) NOT NULL,
    Email        VARCHAR(255) NOT NULL,
    HomeAddress  VARCHAR(255) NOT NULL,
    RegisterDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table pour sauvegarder les prêts des clients.
CREATE TABLE IF NOT EXISTS Mortgages
(
    Id           INT AUTO_INCREMENT PRIMARY KEY,
    CustomerId   INT                       NOT NULL,

    Amount       DECIMAL(60, 2)            NOT NULL,
    MortgageType ENUM ('Fixe', 'Variable') NOT NULL,
    Deadline     TIMESTAMP                 NOT NULL,
    Terms        TEXT                      NOT NULL,
    Rate         DECIMAL(60, 10),
    Bank         VARCHAR(25)               NOT NULL,
    Structure    VARCHAR(25)               NOT NULL,

    FOREIGN KEY (CustomerId) REFERENCES Customers (Id)
);

-- Table pour sauvegarder les commentaires mis sur les clients.
CREATE TABLE IF NOT EXISTS Comments
(
    Id           INT AUTO_INCREMENT PRIMARY KEY,
    CustomerId   INT  NOT NULL,
    AuthorId     INT  NOT NULL,

    Body         TEXT NOT NULL,
    RegisterDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (CustomerId) REFERENCES Customers (Id),
    FOREIGN KEY (AuthorId) REFERENCES Users (Id)
);

-- Table pour la réinitialisation de mot de passe.
CREATE TABLE IF NOT EXISTS SMSCodes
(
    Id           INT AUTO_INCREMENT PRIMARY KEY,
    UserId       INT         NOT NULL,

    Code         VARCHAR(10) NOT NULL,
    RegisterDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    DeleteDate   TIMESTAMP   NOT NULL, -- +1 day
);