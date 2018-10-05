use oms;

CREATE TABLE `store` (
    id  INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(45) NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order` (
    id              INT NOT NULL,
    store_id        INT NOT NULL,
    PRIMARY KEY  (id, store_id),
    CONSTRAINT fk_store_id FOREIGN KEY (store_id) REFERENCES `store` (id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `order_line` (
    id          INT NOT NULL,
    order_id    INT NOT NULL,
    sku         VARCHAR(256) NULL,
    store_id    INT NOT NULL,
    CONSTRAINT fk_order_id FOREIGN KEY (order_id) REFERENCES `order` (id),
    CONSTRAINT fk_ol_store_id FOREIGN KEY (store_id) REFERENCES `store` (id)
    ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
