CREATE DATABASE `stock`
CHARACTER SET 'utf8'
COLLATE 'utf8_general_ci';

CREATE TABLE `User` (
`UserID` int AUTO_INCREMENT,
`Username` varchar(40) NOT NULL UNIQUE,
`Password` varchar(40) NOT NULL default '',
`Role` varchar(40) NOT NULL default 'user',
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (UserID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Product` (
`ProductID` int AUTO_INCREMENT,
`Name` varchar(40) NOT NULL,
`Model` varchar(40) NOT NULL,
`Cost` float(8,1) NOT NULL,
`Price` float(8,1) NOT NULL,
`Quantity` int NOT NULL,
`Description` varchar(40),
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (ProductID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Customer` (
`CustomerID` int AUTO_INCREMENT,
`Name` varchar(40) NOT NULL,
`Address` varchar(40) NOT NULL,
`Phone` varchar(40) NOT NULL,
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (CustomerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `Warranty` (
`WarrantyID` int AUTO_INCREMENT,
`CustomerID` int NOT NULL, 
`ProductID` int NOT NULL, 
`StartDate` date NOT NULL,
`ExpiredDate` date NOT NULL,
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (WarrantyID),
FOREIGN KEY (ProductID) REFERENCES Product(ProductID),
FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `Order` (
`OrderID` int AUTO_INCREMENT,
`CustomerID` int NOT NULL, 
`ScheduledDateTime` datetime NOT NULL,
`Status` varchar NOT NULL default 'pending',
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (OrderID),
FOREIGN KEY (CustomerID) REFERENCES Customer(ID)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `OrderDetails` (
`OrderID` int NOT NULL,
`ProductID` int NOT NULL, 
`Quantity` int NOT NULL,
`Price` int NOT NULL,
`UpdatedAt` TIMESTAMP,
`CreatedAt` TIMESTAMP default CURRENT_TIMESTAMP,
PRIMARY KEY (OrderID, ProductID),
FOREIGN KEY (OrderID) REFERENCES Order(OrderID),
FOREIGN KEY (ProductID) REFERENCES Product(ID),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
