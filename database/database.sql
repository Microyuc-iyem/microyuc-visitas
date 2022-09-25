CREATE TABLE carta
(
    id                      INT           NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha_creacion          TIMESTAMP     NOT NULL,
    numero_expediente       VARCHAR(255)  NOT NULL,
    nombre_cliente          VARCHAR(255)  NOT NULL,
    calle                   VARCHAR(255)  NOT NULL,
    cruzamientos            VARCHAR(255)  NOT NULL,
    numero_direccion        VARCHAR(255)  NOT NULL,
    colonia_fraccionamiento VARCHAR(255)  NOT NULL,
    localidad               VARCHAR(255)  NOT NULL,
    municipio               VARCHAR(255)  NOT NULL,
    fecha_firma             DATE          NOT NULL,
    documentacion           TEXT,
    comprobacion_monto      FLOAT(200, 2) NOT NULL,
    comprobacion_tipo       VARCHAR(255)  NOT NULL,
    pagos_fecha_inicial     VARCHAR(255)  NOT NULL,
    pagos_fecha_final       VARCHAR(255)  NOT NULL,
    modalidad               VARCHAR(255)  NOT NULL,
    tipo_credito            VARCHAR(255)  NOT NULL,
    fecha_otorgamiento      DATE          NOT NULL,
    monto_inicial           FLOAT(200, 2) NOT NULL,
    mensualidades_vencidas  INT           NOT NULL,
    adeudo_total            FLOAT(200, 2) NOT NULL,
    nombre_archivo          VARCHAR(255)
);

CREATE TABLE bitacora
(
    id                              INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fecha_creacion                  TIMESTAMP    NOT NULL,
    acreditado_nombre               VARCHAR(255) NOT NULL,
    acreditado_folio                VARCHAR(255) NOT NULL,
    acreditado_municipio            VARCHAR(255) NOT NULL,
    acreditado_localidad            VARCHAR(255) NOT NULL,
    tipo_garantia                   VARCHAR(255) NOT NULL,
    acreditado_garantia             VARCHAR(255) NOT NULL,
    acreditado_telefono             VARCHAR(255) NOT NULL,
    acreditado_email                VARCHAR(255) NOT NULL,
    acreditado_direccion_negocio    VARCHAR(255) NOT NULL,
    acreditado_direccion_particular VARCHAR(255) NOT NULL,
    aval_nombre                     VARCHAR(255),
    aval_telefono                   VARCHAR(255),
    aval_email                      VARCHAR(255),
    aval_direccion                  VARCHAR(255),
    gestion_fecha1                  VARCHAR(255) DEFAULT '',
    gestion_via1                    VARCHAR(255) DEFAULT '',
    gestion_comentarios1            VARCHAR(255) DEFAULT '',
    gestion_contador                INT          NOT NULL,
    evidencia_fecha1                VARCHAR(255) DEFAULT '',
    evidencia_fotografia1           VARCHAR(255) DEFAULT '',
    evidencia_contador              INT          NOT NULL,
    nombre_archivo                  VARCHAR(255) NOT NULL
);

CREATE TABLE usuario
(
    id       INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre   VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

INSERT INTO usuario
VALUES (null, 'Admin', '123456789@MY');