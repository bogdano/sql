CONNECT bb210898;

DROP TABLE PATRON_FOLLOWS_VENUE;
DROP TABLE PATRON_ATTENDS_GIG;
DROP TABLE PATRON_FOLLOWS_BAND;
DROP TABLE MUSICIAN_PLAYS_FOR_BAND;
DROP TABLE MUSICIAN_RATING;
DROP TABLE BAND_RATING;
DROP TABLE VENUE_RATING;
DROP TABLE GIG_REVIEW;
DROP TABLE GIG;
DROP TABLE PATRON_STREAMING_PLATFORMS;
DROP TABLE PATRON_GENRES;
DROP TABLE M_GENRES;
DROP TABLE M_INSTRUMENTS;
DROP TABLE M_DEMOS;
DROP TABLE BAND_SOC_MEDIA;
DROP TABLE BAND_GENRES;
DROP TABLE BAND;
DROP TABLE MUSICIAN;
DROP TABLE MANAGER;
DROP TABLE PATRON;
DROP TABLE VENUE;

CREATE TABLE VENUE (
  v_email VARCHAR(340) PRIMARY KEY,
  v_name VARCHAR(100) NOT NULL,
  v_type VARCHAR(20) NOT NULL, /* CHANGE THIS ADD DOMAIN */
  capacity SMALLINT NOT NULL,
  price_range CHAR(5) DEFAULT "*" CHECK (price_range IN ('*', '**', '***', '****', '*****', NULL)), /* AN EXAMPLE OF A DOMAIN CHECK */
  v_address VARCHAR(50) NOT NULL,
  v_number VARCHAR(20) NOT NULL,
  dancefloor BOOLEAN NOT NULL DEFAULT FALSE,
  hours VARCHAR(50)
);

CREATE TABLE PATRON (
  p_email VARCHAR(340) PRIMARY KEY,
  p_name VARCHAR(100),
  p_birthdate DATE,
  profile_picture VARCHAR(340)
);

CREATE TABLE PATRON_GENRES (
  p_email VARCHAR(340),
  p_genre VARCHAR(100), /* CHANGE THIS ADD DOMAIN */
  PRIMARY KEY (p_email, p_genre),
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PATRON_STREAMING_PLATFORMS (
  p_email VARCHAR(340),
  p_platform VARCHAR(500),
  PRIMARY KEY (p_email, p_platform),
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE MANAGER (
  mgr_email VARCHAR(340) PRIMARY KEY,
  mgr_name VARCHAR(100) NOT NULL,
  mgr_address VARCHAR(500) NOT NULL,
  mgr_number VARCHAR(20) NOT NULL,
  website VARCHAR(500)
);

CREATE TABLE BAND (
  b_email VARCHAR(340) PRIMARY KEY,
  b_name VARCHAR(100) NOT NULL,
  b_location VARCHAR(100) NOT NULL,
  forming_year SMALLINT(4),
  b_desc VARCHAR(1500),
  mgr_email VARCHAR(340),
  FOREIGN KEY (mgr_email) REFERENCES MANAGER (mgr_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE BAND_GENRES (
  b_email VARCHAR(340),
  b_genre VARCHAR(100), /* CHANGE THIS ADD DOMAIN */
  PRIMARY KEY (b_email, b_genre),
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE BAND_SOC_MEDIA (
  b_email VARCHAR(340),
  soc_media VARCHAR(500),
  PRIMARY KEY (b_email, soc_media),
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE MUSICIAN (
  m_email VARCHAR(340) PRIMARY KEY,
  m_name VARCHAR(100) NOT NULL,
  m_birthdate DATE,
  vocals BOOLEAN DEFAULT FALSE,
  fulltime BOOLEAN DEFAULT FALSE,
  m_desc VARCHAR(1500)
);

CREATE TABLE M_DEMOS (
  m_email VARCHAR(340),
  m_demo VARCHAR(500), /* RIGHT NOW MEANT FOR LINKS */
  PRIMARY KEY (m_email, m_demo),
  FOREIGN KEY (m_email) REFERENCES MUSICIAN (m_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE M_INSTRUMENTS (
  m_email VARCHAR(340),
  m_instrument VARCHAR(100), /* CHANGE THIS ADD DOMAIN */
  PRIMARY KEY (m_email, m_instrument),
  FOREIGN KEY (m_email) REFERENCES MUSICIAN (m_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE M_GENRES (
  m_email VARCHAR(340),
  m_genre VARCHAR(100), /* CHANGE THIS ADD DOMAIN */
  PRIMARY KEY (m_email, m_genre),
  FOREIGN KEY (m_email) REFERENCES MUSICIAN (m_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE MUSICIAN_RATING (
  m_email VARCHAR(340),
  b_email VARCHAR(340),
  b_recommends BOOLEAN NOT NULL DEFAULT TRUE,
  musician_rating_text VARCHAR(500),
  PRIMARY KEY (m_email, b_email),
  FOREIGN KEY (m_email) REFERENCES MUSICIAN (m_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE GIG (
  gig_date DATE,
  gig_time TIME(0) NOT NULL,
  price SMALLINT(3),
  overage BOOLEAN DEFAULT TRUE,
  v_email VARCHAR(340),
  b_email VARCHAR(340),
  PRIMARY KEY (gig_date, v_email, b_email),
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE GIG_REVIEW (
  gig_date DATE, 
  v_email VARCHAR(340),
  b_email VARCHAR(340),
  p_email VARCHAR(340),
  music_rating CHAR(5) DEFAULT "*****" NOT NULL,
  review_text VARCHAR(500),
  staff CHAR(5),
  cleanliness CHAR(5),
  sound CHAR(5),
  p_recommends BOOLEAN NOT NULL DEFAULT TRUE,
  prices CHAR(5),
  PRIMARY KEY (gig_date, v_email, b_email, p_email),
  FOREIGN KEY (gig_date) REFERENCES GIG (gig_date)  ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE VENUE_RATING (
  v_email VARCHAR(340),
  b_email VARCHAR(340),
  b_recommends BOOLEAN NOT NULL DEFAULT TRUE,
  m_rating_text VARCHAR(500),
  PRIMARY KEY (v_email, b_email),
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE BAND_RATING (
  v_recommends BOOLEAN DEFAULT TRUE,
  b_email VARCHAR(340),
  v_email VARCHAR(340),
  PRIMARY KEY (b_email, v_email),
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE MUSICIAN_PLAYS_FOR_BAND (
  m_email VARCHAR(340),
  b_email VARCHAR(340),
  PRIMARY KEY (m_email, b_email),
  FOREIGN KEY (m_email) REFERENCES MUSICIAN (m_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PATRON_FOLLOWS_BAND (
  p_email VARCHAR(340),
  b_email VARCHAR(340),
  follow_date DATE,
  PRIMARY KEY (p_email, b_email),
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PATRON_ATTENDS_GIG (
  p_email VARCHAR(340),
  b_email VARCHAR(340),
  v_email VARCHAR(340),
  gig_date DATE,
  PRIMARY KEY (p_email, b_email, v_email, gig_date),
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (b_email) REFERENCES BAND (b_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (gig_date) REFERENCES GIG (gig_date) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE PATRON_FOLLOWS_VENUE (
  p_email VARCHAR(340),
  v_email VARCHAR(340),
  PRIMARY KEY (p_email, v_email),
  FOREIGN KEY (p_email) REFERENCES PATRON (p_email) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (v_email) REFERENCES VENUE (v_email) ON DELETE CASCADE ON UPDATE CASCADE
);

DESCRIBE VENUE;
DESCRIBE PATRON;
DESCRIBE MANAGER;
DESCRIBE MUSICIAN;
DESCRIBE BAND;
DESCRIBE BAND_GENRES;
DESCRIBE BAND_SOC_MEDIA;
DESCRIBE M_DEMOS;
DESCRIBE M_INSTRUMENTS;
DESCRIBE M_GENRES;
DESCRIBE PATRON_GENRES;
DESCRIBE PATRON_STREAMING_PLATFORMS;
DESCRIBE GIG;
DESCRIBE GIG_REVIEW;
DESCRIBE VENUE_RATING;
DESCRIBE BAND_RATING;
DESCRIBE MUSICIAN_RATING;
DESCRIBE MUSICIAN_PLAYS_FOR_BAND;
DESCRIBE PATRON_FOLLOWS_BAND;
DESCRIBE PATRON_ATTENDS_GIG;
DESCRIBE PATRON_FOLLOWS_VENUE;