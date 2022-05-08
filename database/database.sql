CREATE TABLE carta
(
    carta_id                int          NOT NULL PRIMARY KEY AUTO_INCREMENT,
    fecha_creacion          timestamp DEFAULT CURRENT_TIMESTAMP,
    numero_expediente       varchar(255) NOT NULL,
    nombre_cliente          varchar(255) NOT NULL,
    calle                   varchar(255) NOT NULL,
    cruzamientos            varchar(255) NOT NULL,
    numero_direccion        varchar(255) NOT NULL,
    colonia_fraccionamiento varchar(255) NOT NULL,
    localidad               varchar(255) NOT NULL,
    municipio               varchar(255) NOT NULL,
    fecha_firma             date         NOT NULL,
    documentacion           text,
    comprobacion_monto      bigint       NOT NULL,
    comprobacion_tipo       varchar(255) NOT NULL,
    pagos_fecha_inicial     varchar(255) NOT NULL,
    pagos_fecha_final       varchar(255) NOT NULL,
    tipo_credito            varchar(255) NOT NULL,
    fecha_otorgamiento      date         NOT NULL,
    monto_inicial           bigint       NOT NULL,
    mensualidades_vencidas  int          NOT NULL,
    adeudo_total            bigint       NOT NULL
)

