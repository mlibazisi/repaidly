create table loans(
  loan_id BIGINT(20) NOT NULL,
  loan_amount DECIMAL(13,4) NOT NULL,
  total_lenders SMALLINT(5),
  repayment_months TINYINT(2) NOT NULL,
  PRIMARY KEY( loan_id )
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table borrower_schedules(
  id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
  loan_id BIGINT(20) NOT NULL,
  payment_date DATE NOT NULL,
  payment_amount DECIMAL(13,4) NOT NULL,
  FOREIGN KEY ( loan_id ) REFERENCES loans( loan_id ) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;

create table lender_schedules(
  id BIGINT(20) PRIMARY KEY AUTO_INCREMENT,
  lender_id VARCHAR(60) NOT NULL,
  loan_id BIGINT(20) NOT NULL,
  receipt_date DATE NOT NULL,
  receipt_amount DECIMAL(13,4) NOT NULL,
  FOREIGN KEY ( loan_id ) REFERENCES loans( loan_id ) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=INNODB DEFAULT CHARSET=utf8;