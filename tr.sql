-- جدول الطلاب
CREATE TABLE Students (
    StudentID INT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Password VARCHAR(255),
    Phone VARCHAR(15),
    Major VARCHAR(100),
    SupervisorID INT,
    FOREIGN KEY (SupervisorID) REFERENCES Supervisors(SupervisorID)
);

-- جدول جهات التدريب
CREATE TABLE TrainingOrganizations (
    OrganizationID INT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Phone VARCHAR(15),
    Address VARCHAR(255),
    NafadhID VARCHAR(50)
);

-- جدول وحدة التدريب التعاوني
CREATE TABLE TrainingUnit (
    UnitID INT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Phone VARCHAR(15)
);

-- جدول المشرفين التعاونيين
CREATE TABLE Supervisors (
    SupervisorID INT PRIMARY KEY,
    Name VARCHAR(100),
    Email VARCHAR(100),
    Password VARCHAR(255),
    Phone VARCHAR(15),
    UnitID INT,
    FOREIGN KEY (UnitID) REFERENCES TrainingUnit(UnitID)
);

-- جدول فرص التدريب
CREATE TABLE TrainingOpportunities (
    OpportunityID INT PRIMARY KEY,
    Title VARCHAR(100),
    Description TEXT,
    Requirements TEXT,
    OrganizationID INT,
    StartDate DATE,
    EndDate DATE,
    FOREIGN KEY (OrganizationID) REFERENCES TrainingOrganizations(OrganizationID)
);

-- جدول المستندات
CREATE TABLE Documents (
    DocumentID INT PRIMARY KEY,
    StudentID INT,
    OpportunityID INT,
    DocumentType VARCHAR(50),
    FilePath VARCHAR(255),
    UploadDate DATETIME,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID),
    FOREIGN KEY (OpportunityID) REFERENCES TrainingOpportunities(OpportunityID)
);

-- جدول التقارير
CREATE TABLE Reports (
    ReportID INT PRIMARY KEY,
    StudentID INT,
    SupervisorID INT,
    ReportType VARCHAR(50),
    Content TEXT,
    SubmissionDate DATETIME,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID),
    FOREIGN KEY (SupervisorID) REFERENCES Supervisors(SupervisorID)
);

-- جدول التقييمات
CREATE TABLE Evaluations (
    EvaluationID INT PRIMARY KEY,
    StudentID INT,
    OrganizationID INT,
    SupervisorID INT,
    Score INT,
    Comments TEXT,
    EvaluationDate DATETIME,
    FOREIGN KEY (StudentID) REFERENCES Students(StudentID),
    FOREIGN KEY (OrganizationID) REFERENCES TrainingOrganizations(OrganizationID),
    FOREIGN KEY (SupervisorID) REFERENCES Supervisors(SupervisorID)
);

-- جدول الرسائل
CREATE TABLE Messages (
    MessageID INT PRIMARY KEY,
    SenderID INT,
    ReceiverID INT,
    Content TEXT,
    SentDate DATETIME
);