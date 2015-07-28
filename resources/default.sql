
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- airports
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `airports`;

CREATE TABLE `airports`
(
    `id` INTEGER NOT NULL,
    `name` VARCHAR(128) NOT NULL,
    `city` VARCHAR(128) NOT NULL,
    `country` VARCHAR(128) NOT NULL,
    `iata` CHAR(3) NOT NULL,
    `icao` CHAR(4) NOT NULL,
    `altitude` FLOAT DEFAULT 0 NOT NULL,
    `timezone` FLOAT DEFAULT 0 NOT NULL,
    `dst` TINYINT DEFAULT 6 NOT NULL,
    `size` TINYINT DEFAULT 0 NOT NULL,
    `latitude` DOUBLE(10,8),
    `longitude` DOUBLE(10,8),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- aircraft_models
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `aircraft_models`;

CREATE TABLE `aircraft_models`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `model` VARCHAR(12) NOT NULL,
    `brand` VARCHAR(12) NOT NULL,
    `packages` SMALLINT DEFAULT 0 NOT NULL,
    `post` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_low` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_mid` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_high` SMALLINT DEFAULT 0 NOT NULL,
    `weight` INTEGER NOT NULL,
    `value` INTEGER NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `model` (`model`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- airlines
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `airlines`;

CREATE TABLE `airlines`
(
    `id` INTEGER NOT NULL,
    `name` CHAR(255) NOT NULL,
    `alias` CHAR(255) NOT NULL,
    `iata` CHAR(3) NOT NULL,
    `icao` CHAR(3) NOT NULL,
    `callsign` CHAR(255) NOT NULL,
    `country` CHAR(255) NOT NULL,
    `active` TINYINT(1) DEFAULT 0 NOT NULL,
    `saldo` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- aircrafts
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `aircrafts`;

CREATE TABLE `aircrafts`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `aircraft_model_id` INTEGER NOT NULL,
    `airline_id` INTEGER NOT NULL,
    `airport_id` INTEGER,
    `pilot_id` INTEGER NOT NULL,
    `callsign` VARCHAR(7) NOT NULL,
    `flown_distance` INTEGER DEFAULT 0 NOT NULL,
    `number_flights` SMALLINT DEFAULT 0 NOT NULL,
    `flown_time` INTEGER DEFAULT 0 NOT NULL,
    `status` TINYINT DEFAULT 0 NOT NULL,
    `latitude` DOUBLE(10,8),
    `longitude` DOUBLE(10,8),
    PRIMARY KEY (`id`,`aircraft_model_id`,`airline_id`,`pilot_id`),
    UNIQUE INDEX `callsign` (`callsign`),
    INDEX `aircrafts_fi_533ab3` (`aircraft_model_id`),
    INDEX `aircrafts_fi_d3c970` (`airport_id`),
    INDEX `aircrafts_fi_3c541c` (`airline_id`),
    INDEX `aircrafts_fi_17d49f` (`pilot_id`),
    CONSTRAINT `aircrafts_fk_533ab3`
        FOREIGN KEY (`aircraft_model_id`)
        REFERENCES `aircraft_models` (`id`),
    CONSTRAINT `aircrafts_fk_d3c970`
        FOREIGN KEY (`airport_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `aircrafts_fk_3c541c`
        FOREIGN KEY (`airline_id`)
        REFERENCES `airlines` (`id`),
    CONSTRAINT `aircrafts_fk_17d49f`
        FOREIGN KEY (`pilot_id`)
        REFERENCES `pilots` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- flights
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `flights`;

CREATE TABLE `flights`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `aircraft_id` INTEGER NOT NULL,
    `aircraft_model_id` INTEGER NOT NULL,
    `airline_id` INTEGER NOT NULL,
    `destination_id` INTEGER NOT NULL,
    `departure_id` INTEGER NOT NULL,
    `pilot_id` INTEGER NOT NULL,
    `flight_number` VARCHAR(10) NOT NULL,
    `status` TINYINT DEFAULT 0 NOT NULL,
    `packages` SMALLINT DEFAULT 0 NOT NULL,
    `post` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_low` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_mid` SMALLINT DEFAULT 0 NOT NULL,
    `passenger_high` SMALLINT DEFAULT 0 NOT NULL,
    `flight_started_at` DATETIME,
    `flight_finished_at` DATETIME,
    `next_step_possible_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`,`aircraft_id`,`aircraft_model_id`,`airline_id`,`destination_id`,`departure_id`,`pilot_id`),
    INDEX `flights_fi_c3deee` (`aircraft_id`),
    INDEX `flights_fi_533ab3` (`aircraft_model_id`),
    INDEX `flights_fi_3c541c` (`airline_id`),
    INDEX `flights_fi_a39f89` (`destination_id`),
    INDEX `flights_fi_cbe538` (`departure_id`),
    INDEX `flights_fi_17d49f` (`pilot_id`),
    CONSTRAINT `flights_fk_c3deee`
        FOREIGN KEY (`aircraft_id`)
        REFERENCES `aircrafts` (`id`),
    CONSTRAINT `flights_fk_533ab3`
        FOREIGN KEY (`aircraft_model_id`)
        REFERENCES `aircraft_models` (`id`),
    CONSTRAINT `flights_fk_3c541c`
        FOREIGN KEY (`airline_id`)
        REFERENCES `airlines` (`id`),
    CONSTRAINT `flights_fk_a39f89`
        FOREIGN KEY (`destination_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `flights_fk_cbe538`
        FOREIGN KEY (`departure_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `flights_fk_17d49f`
        FOREIGN KEY (`pilot_id`)
        REFERENCES `pilots` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- airways
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `airways`;

CREATE TABLE `airways`
(
    `destination_id` INTEGER NOT NULL,
    `departure_id` INTEGER NOT NULL,
    `next_steps` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`destination_id`,`departure_id`),
    INDEX `airways_fi_cbe538` (`departure_id`),
    CONSTRAINT `airways_fk_a39f89`
        FOREIGN KEY (`destination_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `airways_fk_cbe538`
        FOREIGN KEY (`departure_id`)
        REFERENCES `airports` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- freights
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `freights`;

CREATE TABLE `freights`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `destination_id` INTEGER NOT NULL,
    `departure_id` INTEGER NOT NULL,
    `location_id` INTEGER,
    `flight_id` INTEGER,
    `freight_type` TINYINT DEFAULT 0 NOT NULL,
    `next_steps` TEXT,
    `route_flights` TEXT,
    `amount` SMALLINT DEFAULT 0,
    PRIMARY KEY (`id`,`destination_id`,`departure_id`),
    INDEX `freights_fi_a39f89` (`destination_id`),
    INDEX `freights_fi_cbe538` (`departure_id`),
    INDEX `freights_fi_cee5b1` (`location_id`),
    INDEX `freights_fi_b6d532` (`flight_id`),
    CONSTRAINT `freights_fk_a39f89`
        FOREIGN KEY (`destination_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `freights_fk_cbe538`
        FOREIGN KEY (`departure_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `freights_fk_cee5b1`
        FOREIGN KEY (`location_id`)
        REFERENCES `airports` (`id`),
    CONSTRAINT `freights_fk_b6d532`
        FOREIGN KEY (`flight_id`)
        REFERENCES `flights` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- pilots
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `pilots`;

CREATE TABLE `pilots`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `airline_id` INTEGER,
    `name` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255),
    `rank` TINYINT DEFAULT 1 NOT NULL,
    `saldo` INTEGER DEFAULT 0 NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `token` (`token`),
    INDEX `pilots_fi_3c541c` (`airline_id`),
    CONSTRAINT `pilots_fk_3c541c`
        FOREIGN KEY (`airline_id`)
        REFERENCES `airlines` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- freight_generations
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `freight_generations`;

CREATE TABLE `freight_generations`
(
    `airport_id` INTEGER NOT NULL,
    `next_generation_at` DATETIME NOT NULL,
    `next_update_at` DATETIME NOT NULL,
    `capacity` INTEGER DEFAULT 1 NOT NULL,
    `every` INTEGER DEFAULT 1 NOT NULL,
    PRIMARY KEY (`airport_id`),
    CONSTRAINT `freight_generations_fk_d3c970`
        FOREIGN KEY (`airport_id`)
        REFERENCES `airports` (`id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
