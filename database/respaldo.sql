--
-- PostgreSQL database dump
--

\restrict SzmYzWRB6jxwkQ4VKzS44e8nxFY7FOs9QSyDolNeHc2qIlL3McNGFJhchDHXOMd

-- Dumped from database version 17.8
-- Dumped by pg_dump version 17.8

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: aportes_socios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.aportes_socios (
    id bigint NOT NULL,
    socio_id bigint NOT NULL,
    periodo_mes smallint NOT NULL,
    periodo_anio smallint NOT NULL,
    valor_cuota numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    estado_pago character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    fecha_pago date,
    observacion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: aportes_socios_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.aportes_socios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: aportes_socios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.aportes_socios_id_seq OWNED BY public.aportes_socios.id;


--
-- Name: archivos_adjuntos; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.archivos_adjuntos (
    id bigint NOT NULL,
    archivable_type character varying(255) NOT NULL,
    archivable_id bigint NOT NULL,
    nombre_original character varying(255) NOT NULL,
    ruta character varying(255) NOT NULL,
    mime_type character varying(255),
    tamano bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: archivos_adjuntos_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.archivos_adjuntos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: archivos_adjuntos_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.archivos_adjuntos_id_seq OWNED BY public.archivos_adjuntos.id;


--
-- Name: audit_logs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.audit_logs (
    id bigint NOT NULL,
    user_id bigint,
    accion character varying(255) NOT NULL,
    auditable_type character varying(255) NOT NULL,
    auditable_id bigint NOT NULL,
    valores_anteriores json,
    valores_nuevos json,
    direccion_ip character varying(255),
    user_agent text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: audit_logs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.audit_logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: audit_logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.audit_logs_id_seq OWNED BY public.audit_logs.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: categorias_movimiento; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.categorias_movimiento (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    tipo character varying(255) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: categorias_movimiento_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.categorias_movimiento_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: categorias_movimiento_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.categorias_movimiento_id_seq OWNED BY public.categorias_movimiento.id;


--
-- Name: clientes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.clientes (
    id bigint NOT NULL,
    razon_social character varying(255) NOT NULL,
    ruc character varying(255) NOT NULL,
    contacto character varying(255),
    telefono character varying(255),
    email character varying(255),
    direccion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: clientes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.clientes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: clientes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.clientes_id_seq OWNED BY public.clientes.id;


--
-- Name: factura_distribuciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.factura_distribuciones (
    id bigint NOT NULL,
    factura_id bigint NOT NULL,
    socio_destino_id bigint NOT NULL,
    tipo_distribucion character varying(255) DEFAULT 'interna'::character varying NOT NULL,
    valor numeric(12,2) NOT NULL,
    porcentaje numeric(5,2),
    observacion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    base_calculo numeric(12,2)
);


--
-- Name: factura_distribuciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.factura_distribuciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: factura_distribuciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.factura_distribuciones_id_seq OWNED BY public.factura_distribuciones.id;


--
-- Name: factura_retenciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.factura_retenciones (
    id bigint NOT NULL,
    factura_id bigint NOT NULL,
    tipo_retencion_id bigint NOT NULL,
    porcentaje numeric(5,2) NOT NULL,
    base_calculo numeric(12,2) NOT NULL,
    valor_retencion numeric(12,2) NOT NULL,
    estado character varying(255) DEFAULT 'activo'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: factura_retenciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.factura_retenciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: factura_retenciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.factura_retenciones_id_seq OWNED BY public.factura_retenciones.id;


--
-- Name: facturas; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.facturas (
    id bigint NOT NULL,
    numero_factura character varying(255) NOT NULL,
    fecha_emision date NOT NULL,
    socio_id bigint NOT NULL,
    cliente_id bigint NOT NULL,
    valor_bruto numeric(12,2) NOT NULL,
    valor_recibido numeric(12,2),
    estado_factura character varying(255) DEFAULT 'pendiente'::character varying NOT NULL,
    observacion text,
    created_by bigint,
    updated_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: COLUMN facturas.valor_recibido; Type: COMMENT; Schema: public; Owner: -
--

COMMENT ON COLUMN public.facturas.valor_recibido IS 'Monto real despues de retencion 1%';


--
-- Name: facturas_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.facturas_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: facturas_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.facturas_id_seq OWNED BY public.facturas.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: liquidacion_detalles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.liquidacion_detalles (
    id bigint NOT NULL,
    liquidacion_id bigint NOT NULL,
    factura_id bigint NOT NULL,
    importe_aplicado numeric(12,2) NOT NULL,
    observacion text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: liquidacion_detalles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.liquidacion_detalles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: liquidacion_detalles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.liquidacion_detalles_id_seq OWNED BY public.liquidacion_detalles.id;


--
-- Name: liquidaciones; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.liquidaciones (
    id bigint NOT NULL,
    socio_id bigint NOT NULL,
    periodo_mes smallint NOT NULL,
    periodo_anio smallint NOT NULL,
    total_facturado numeric(12,2) NOT NULL,
    total_retenciones numeric(12,2) NOT NULL,
    total_distribuciones numeric(12,2),
    total_neto numeric(12,2) NOT NULL,
    estado character varying(255) DEFAULT 'borrador'::character varying NOT NULL,
    firma_socio text,
    fecha_generacion date NOT NULL,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: liquidaciones_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.liquidaciones_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: liquidaciones_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.liquidaciones_id_seq OWNED BY public.liquidaciones.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: movimientos_caja; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.movimientos_caja (
    id bigint NOT NULL,
    fecha date NOT NULL,
    tipo character varying(255) NOT NULL,
    categoria_id bigint NOT NULL,
    descripcion text NOT NULL,
    valor numeric(12,2) NOT NULL,
    referencia_tipo character varying(255),
    referencia_id bigint,
    estado character varying(255) DEFAULT 'activo'::character varying NOT NULL,
    created_by bigint,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: movimientos_caja_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.movimientos_caja_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: movimientos_caja_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.movimientos_caja_id_seq OWNED BY public.movimientos_caja.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: permission_role; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permission_role (
    permission_id bigint NOT NULL,
    role_id bigint NOT NULL
);


--
-- Name: permissions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.permissions (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: permissions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.permissions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: permissions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.permissions_id_seq OWNED BY public.permissions.id;


--
-- Name: role_user; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.role_user (
    role_id bigint NOT NULL,
    user_id bigint NOT NULL
);


--
-- Name: roles; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.roles (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: roles_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.roles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: roles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.roles_id_seq OWNED BY public.roles.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: socios; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.socios (
    id bigint NOT NULL,
    nombres character varying(255) NOT NULL,
    identificacion character varying(255) NOT NULL,
    telefono character varying(255),
    email character varying(255),
    direccion text,
    cuota_mensual_base numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    porcentaje_participacion numeric(5,2),
    tipo_socio character varying(255),
    activo boolean DEFAULT true NOT NULL,
    fecha_registro timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    deleted_at timestamp(0) without time zone
);


--
-- Name: socios_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.socios_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: socios_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.socios_id_seq OWNED BY public.socios.id;


--
-- Name: tipos_retencion; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.tipos_retencion (
    id bigint NOT NULL,
    nombre character varying(255) NOT NULL,
    slug character varying(255) NOT NULL,
    porcentaje numeric(5,2) NOT NULL,
    descripcion text,
    activo boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: tipos_retencion_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.tipos_retencion_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: tipos_retencion_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.tipos_retencion_id_seq OWNED BY public.tipos_retencion.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    role_id bigint,
    activo boolean DEFAULT true NOT NULL,
    socio_id bigint
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: aportes_socios id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aportes_socios ALTER COLUMN id SET DEFAULT nextval('public.aportes_socios_id_seq'::regclass);


--
-- Name: archivos_adjuntos id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.archivos_adjuntos ALTER COLUMN id SET DEFAULT nextval('public.archivos_adjuntos_id_seq'::regclass);


--
-- Name: audit_logs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs ALTER COLUMN id SET DEFAULT nextval('public.audit_logs_id_seq'::regclass);


--
-- Name: categorias_movimiento id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorias_movimiento ALTER COLUMN id SET DEFAULT nextval('public.categorias_movimiento_id_seq'::regclass);


--
-- Name: clientes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clientes ALTER COLUMN id SET DEFAULT nextval('public.clientes_id_seq'::regclass);


--
-- Name: factura_distribuciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_distribuciones ALTER COLUMN id SET DEFAULT nextval('public.factura_distribuciones_id_seq'::regclass);


--
-- Name: factura_retenciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_retenciones ALTER COLUMN id SET DEFAULT nextval('public.factura_retenciones_id_seq'::regclass);


--
-- Name: facturas id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas ALTER COLUMN id SET DEFAULT nextval('public.facturas_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: liquidacion_detalles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidacion_detalles ALTER COLUMN id SET DEFAULT nextval('public.liquidacion_detalles_id_seq'::regclass);


--
-- Name: liquidaciones id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidaciones ALTER COLUMN id SET DEFAULT nextval('public.liquidaciones_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: movimientos_caja id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.movimientos_caja ALTER COLUMN id SET DEFAULT nextval('public.movimientos_caja_id_seq'::regclass);


--
-- Name: permissions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions ALTER COLUMN id SET DEFAULT nextval('public.permissions_id_seq'::regclass);


--
-- Name: roles id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles ALTER COLUMN id SET DEFAULT nextval('public.roles_id_seq'::regclass);


--
-- Name: socios id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.socios ALTER COLUMN id SET DEFAULT nextval('public.socios_id_seq'::regclass);


--
-- Name: tipos_retencion id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipos_retencion ALTER COLUMN id SET DEFAULT nextval('public.tipos_retencion_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: aportes_socios; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.aportes_socios (id, socio_id, periodo_mes, periodo_anio, valor_cuota, estado_pago, fecha_pago, observacion, created_at, updated_at) FROM stdin;
1	1	1	2026	75.00	pagado	2026-01-30	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
2	3	1	2026	75.00	pagado	2026-03-09	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
3	4	1	2026	0.00	cancelado	2026-01-19	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
4	2	1	2026	150.00	pagado	2026-02-02	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
5	5	1	2026	0.00	exento	\N	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
6	6	1	2026	75.00	pagado	2026-02-01	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
7	1	2	2026	75.00	pagado	2026-02-06	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
8	3	2	2026	75.00	pagado	2026-03-09	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
9	4	2	2026	75.00	pagado	2026-02-06	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
10	2	2	2026	150.00	pagado	2026-02-15	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
11	5	2	2026	0.00	exento	\N	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
12	6	2	2026	75.00	pagado	2026-03-09	\N	2026-06-30 19:22:54	2026-06-30 19:22:54
13	1	3	2026	75.00	pagado	2026-03-03	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
14	3	3	2026	75.00	pagado	2026-06-15	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
15	4	3	2026	75.00	pagado	2026-03-13	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
16	2	3	2026	150.00	pagado	2026-03-05	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
17	5	3	2026	0.00	exento	\N	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
18	6	3	2026	75.00	pagado	2026-03-14	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
19	1	4	2026	75.00	pagado	2026-04-09	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
20	3	4	2026	75.00	pagado	2026-06-15	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
21	4	4	2026	75.00	pagado	2026-04-05	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
22	2	4	2026	150.00	pagado	2026-04-05	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
23	5	4	2026	0.00	exento	\N	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
24	6	4	2026	75.00	pagado	2026-04-05	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
25	1	5	2026	75.00	pagado	2026-05-01	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
26	3	5	2026	75.00	pagado	2026-06-15	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
27	4	5	2026	75.00	pagado	2026-05-01	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
28	2	5	2026	150.00	pagado	2026-05-01	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
29	5	5	2026	0.00	exento	\N	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
30	6	5	2026	75.00	pagado	2026-05-01	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
31	1	6	2026	75.00	pagado	2026-06-02	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
32	3	6	2026	75.00	pagado	2026-06-15	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
33	4	6	2026	75.00	pagado	2026-06-12	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
34	2	6	2026	150.00	pagado	2026-06-12	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
35	5	6	2026	0.00	exento	\N	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
36	6	6	2026	75.00	pagado	2026-06-12	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
37	1	7	2026	75.00	pagado	2026-07-02	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
38	4	7	2026	75.00	pagado	2026-07-01	\N	2026-07-09 18:46:54	2026-07-09 18:46:54
\.


--
-- Data for Name: archivos_adjuntos; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.archivos_adjuntos (id, archivable_type, archivable_id, nombre_original, ruta, mime_type, tamano, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: audit_logs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.audit_logs (id, user_id, accion, auditable_type, auditable_id, valores_anteriores, valores_nuevos, direccion_ip, user_agent, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: cache; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache (key, value, expiration) FROM stdin;
\.


--
-- Data for Name: cache_locks; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.cache_locks (key, owner, expiration) FROM stdin;
\.


--
-- Data for Name: categorias_movimiento; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.categorias_movimiento (id, nombre, tipo, descripcion, activo, created_at, updated_at) FROM stdin;
1	Pago a contador	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
2	Movilizacion	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
3	Servicios bancarios	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
4	Emision de facturas	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
5	Diferencia al contador	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
6	Chequera	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
7	IESS	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
8	Protesta de cheques	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
9	Renovacion de factura	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
10	Aporte agencia de transito	egreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
11	Cuota administrativa	ingreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
12	Valor recibido facturas	ingreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
13	Retencion 3%	ingreso	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54
14	Prueba	ingreso	\N	t	2026-07-08 02:05:12	2026-07-08 02:05:12
15	Pago a la Marea	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
16	Permiso Bomberos	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
17	Informe Comisario	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
18	Declaracion Impuestos	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
19	Declaracion IVA	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
20	Declaracion Retencion	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
21	Retencion anos anteriores	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
22	Super de Companias	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
23	Patentes y Licencias	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
24	Hojas y tinta	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
25	Tramite Bomberos	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
26	Certificado Bancario	egreso	\N	t	2026-07-09 18:46:54	2026-07-09 18:46:54
\.


--
-- Data for Name: clientes; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.clientes (id, razon_social, ruc, contacto, telefono, email, direccion, activo, created_at, updated_at, deleted_at) FROM stdin;
1	ECUANATICA S.A	ECUA001	\N	\N	\N	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
2	PACIFICTUNA S.A	PAC001	\N	\N	\N	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
3	TRABEL AGENCY MEETECUADOR	TRABEL001	\N	\N	\N	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
4	MOKACHINOEXPRES S.A	MOKA001	\N	\N	\N	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
5	GUAYATUNA S.A	GUAYA001	\N	\N	\N	\N	t	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
6	FUNDACION GRUPOS MISIONEROS	FUNDACIONG	\N	\N	\N	\N	t	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
7	SAMAY ADVENTURES	SAMAYADVEN	\N	\N	\N	\N	t	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
8	VALDIVIESO TAPIA IAN	VALDIVIESO	\N	\N	\N	\N	t	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
9	KELVIN SOLORZANO	KELVINSOLO	\N	\N	\N	\N	t	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
10	MARIA SOLEDAD NAVAS	MARIASOLED	\N	\N	\N	\N	t	2026-07-09 20:19:55	2026-07-09 20:19:55	\N
11	IGLESIA DE JESUCRISTO	IGLESIADEJ	\N	\N	\N	\N	t	2026-07-09 20:19:55	2026-07-09 20:19:55	\N
12	FLAVIO MENDOZA	FLAVIOMEND	\N	\N	\N	\N	t	2026-07-09 20:19:55	2026-07-09 20:19:55	\N
13	PINTURAS EL MAESTRO	PINTURASEL	\N	\N	\N	\N	t	2026-07-09 20:19:55	2026-07-09 20:19:55	\N
14	ALEX PAREDES	ALEX001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
15	UNIVERSIDAD UTE	UTE001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
16	NARWELL TOURS	NARW001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
17	ANDESPORTS S.A.	ANDE001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
18	VANSERTRANS S.A	VANS001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
19	HOTEL ORO VERDE MANDA	OROV001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
20	TRAVEL AGENCY MEETECUADOR	TRAV001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
21	CARRILLO CHICO REGULO	CARR001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
22	SWEADEN S.A	SWEA001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
23	GRUPO MANCHENO	GRUP001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
24	TRANSEPICENTRO S.A	TRANS001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
25	CHIGUANO YANGUISELA MARCO	CHIG001	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
26	ANULADA	ANULADA	\N	\N	\N	\N	t	2026-07-09 20:47:23	2026-07-09 20:47:37	2026-07-09 20:47:37
\.


--
-- Data for Name: factura_distribuciones; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.factura_distribuciones (id, factura_id, socio_destino_id, tipo_distribucion, valor, porcentaje, observacion, created_at, updated_at, base_calculo) FROM stdin;
4	2	2	interna	2.50	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
5	2	2	interna	16.34	\N	Distribucion a Freddy	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
6	2	1	interna	11.88	\N	Distribucion a Nelson	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
7	3	2	interna	5.50	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
8	3	2	interna	9.00	\N	Distribucion a Freddy	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
9	3	1	interna	14.12	\N	Distribucion a Nelson	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
10	4	2	interna	2.25	\N	Distribucion a Freddy	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
11	4	1	interna	4.60	\N	Distribucion a Nelson	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
12	5	1	interna	4.00	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
13	5	1	interna	13.07	\N	Distribucion a Nelson	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
14	6	2	interna	35.02	\N	Distribucion a Freddy	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
15	6	1	interna	48.57	\N	Distribucion a Nelson	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
16	7	1	interna	4.76	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
17	8	1	interna	1.55	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
18	8	2	interna	83.59	\N	Distribucion a Freddy	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
19	9	1	interna	4.40	1.00	Distribucion 1%	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
20	1	1	interna	1.65	1.00	Distribucion 1%	2026-07-09 17:32:53	2026-07-09 17:32:53	165.00
21	1	1	interna	7.43	\N	Distribucion a Freddy	2026-07-09 17:32:53	2026-07-09 17:32:53	165.00
22	1	1	interna	4.90	\N	Distribucion a Nelson	2026-07-09 17:32:53	2026-07-09 17:32:53	165.00
23	10	2	interna	4.80	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
24	11	1	interna	1.70	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
25	12	1	interna	2.88	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
26	13	1	interna	1.60	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
27	14	1	interna	4.86	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
28	15	1	interna	0.83	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
29	16	4	interna	1.50	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
30	19	1	interna	4.12	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
31	20	1	interna	1.60	1.00	Distribucion 1%	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
32	24	2	interna	10.80	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
33	27	2	interna	2.00	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
34	28	4	interna	6.00	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
35	29	1	interna	1.60	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
36	30	2	interna	5.00	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
37	31	1	interna	1.50	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
38	32	1	interna	7.50	1.00	Distribucion 1%	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
58	43	1	interna	3.74	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
59	44	1	interna	3.03	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
60	45	1	interna	3.01	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
61	46	2	interna	9.00	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
62	48	2	interna	0.90	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
63	49	2	interna	4.00	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
64	50	1	interna	6.75	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
65	51	1	interna	0.80	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
66	52	1	interna	0.80	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
67	54	4	interna	4.00	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
68	55	2	interna	6.50	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
69	56	2	interna	10.80	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
70	58	1	interna	4.78	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
71	59	2	interna	6.00	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
72	60	1	interna	4.62	1.00	Distribucion 1%	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
\.


--
-- Data for Name: factura_retenciones; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.factura_retenciones (id, factura_id, tipo_retencion_id, porcentaje, base_calculo, valor_retencion, estado, created_at, updated_at) FROM stdin;
2	2	2	3.00	247.50	7.42	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
3	3	2	3.00	544.50	16.34	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
4	4	2	3.00	300.00	9.00	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
5	5	2	3.00	396.00	11.88	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
6	6	2	3.00	75.00	2.25	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
7	7	2	3.00	470.75	14.12	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
8	8	2	3.00	153.45	4.60	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
9	9	2	3.00	435.60	13.07	activo	2026-06-30 19:22:54	2026-06-30 19:22:54
10	1	2	3.00	163.35	4.90	activo	2026-07-09 17:32:53	2026-07-09 17:32:53
11	10	2	3.00	475.20	14.26	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
12	11	2	3.00	168.30	5.05	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
13	12	2	3.00	285.12	8.55	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
14	13	2	3.00	158.40	4.75	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
15	14	2	3.00	481.14	14.43	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
17	16	2	3.00	148.50	4.46	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
18	17	2	3.00	2240.00	67.20	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
19	18	2	3.00	1000.00	30.00	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
20	19	2	3.00	407.88	12.24	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
21	20	2	3.00	158.40	4.75	activo	2026-07-09 20:15:14	2026-07-09 20:15:14
16	15	2	3.00	82.17	2.46	activo	2026-07-09 20:15:14	2026-07-09 20:17:03
22	21	2	3.00	240.00	7.20	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
23	22	2	3.00	60.00	1.80	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
24	23	2	3.00	390.00	9.60	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
25	24	2	3.00	1069.20	32.08	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
26	25	2	3.00	450.00	13.50	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
27	26	2	3.00	700.00	21.00	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
28	27	2	3.00	198.00	5.94	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
29	28	2	3.00	594.00	17.82	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
31	30	2	3.00	495.00	14.85	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
32	31	2	3.00	148.50	4.46	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
33	32	2	3.00	742.50	22.28	activo	2026-07-09 20:20:14	2026-07-09 20:20:14
30	29	2	3.00	158.40	4.74	activo	2026-07-09 20:20:14	2026-07-09 20:20:22
43	42	2	3.00	180.00	5.40	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
44	43	2	3.00	370.26	11.11	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
45	44	2	3.00	299.97	9.00	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
46	45	2	3.00	297.99	8.94	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
47	46	2	3.00	891.00	26.73	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
48	47	2	3.00	640.00	19.20	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
49	48	2	3.00	89.10	2.67	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
50	49	2	3.00	396.00	11.88	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
52	51	2	3.00	79.20	2.38	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
53	52	2	3.00	79.20	2.38	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
54	54	2	3.00	396.00	11.88	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
55	55	2	3.00	643.50	19.31	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
56	56	2	3.00	1069.20	32.08	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
57	57	2	3.00	230.00	6.90	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
59	59	2	3.00	594.00	17.82	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
60	60	2	3.00	457.38	13.72	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
61	61	2	3.00	220.00	6.60	activo	2026-07-09 20:47:23	2026-07-09 20:47:23
51	50	2	3.00	668.25	20.03	activo	2026-07-09 20:47:23	2026-07-09 20:49:27
58	58	2	3.00	472.73	11.22	activo	2026-07-09 20:47:23	2026-07-09 20:51:46
\.


--
-- Data for Name: facturas; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.facturas (id, numero_factura, fecha_emision, socio_id, cliente_id, valor_bruto, valor_recibido, estado_factura, observacion, created_by, updated_by, created_at, updated_at, deleted_at) FROM stdin;
2	181	2026-01-13	2	2	250.00	247.50	pendiente	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
3	182	2026-01-13	2	3	550.00	544.50	pendiente	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
4	183	2026-01-16	2	4	300.00	300.00	pendiente	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
5	184	2026-01-16	1	1	400.00	396.00	PAGADO	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
6	185	2026-01-17	2	4	75.00	75.00	pendiente	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
7	186	2026-01-19	1	5	475.50	470.75	PAGADO	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
8	187	2026-01-19	1	5	155.00	153.45	PAGADO	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
9	188	2026-01-20	1	1	440.00	435.60	PAGADO	\N	1	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
1	180	2026-01-12	1	1	165.00	163.35	pendiente	\N	1	1	2026-06-30 19:22:54	2026-07-09 17:32:53	\N
11	190	2026-02-02	1	1	170.00	168.30	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
12	191	2026-02-02	1	1	288.00	285.12	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
13	192	2026-02-02	1	1	160.00	158.40	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
14	193	2026-02-02	1	5	486.00	481.14	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
15	194	2026-02-02	1	1	83.00	82.17	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
16	195	2026-02-09	4	7	150.00	148.50	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
17	196	2026-02-19	2	8	2240.00	2240.00	pagado	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
18	197	2026-02-22	2	9	1000.00	1000.00	pagado	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
19	198	2026-02-23	1	1	412.00	407.88	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
20	199	2026-02-23	1	1	160.00	158.40	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:14	\N
10	189	2026-02-02	2	6	480.00	475.20	PAGADO	\N	1	\N	2026-07-09 20:15:14	2026-07-09 20:15:31	\N
21	200	2026-03-03	1	10	240.00	240.00	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
22	201	2026-03-03	1	10	60.00	60.00	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
23	202	2026-03-05	6	4	390.00	390.00	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
24	204	2026-03-07	2	11	1080.00	1069.20	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
25	205	2026-03-10	2	4	450.00	450.00	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
26	206	2026-03-12	1	12	700.00	700.00	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
27	207	2026-03-14	2	13	200.00	198.00	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
28	208	2026-03-15	4	3	600.00	594.00	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
29	209	2026-03-17	1	1	160.00	158.40	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
30	210	2026-03-19	2	6	500.00	495.00	pagado	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
31	211	2026-03-21	1	5	150.00	148.50	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
32	212	2026-03-22	1	5	750.00	742.50	PAGADO	\N	1	\N	2026-07-09 20:20:14	2026-07-09 20:20:14	\N
42	213	2026-04-30	2	14	180.00	180.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
43	214	2026-04-30	1	1	374.00	370.26	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
44	215	2026-04-30	1	1	303.00	299.97	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
45	216	2026-04-30	1	1	301.00	297.99	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
46	217	2026-04-30	2	15	900.00	891.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
47	218	2026-04-30	2	16	640.00	640.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
48	219	2026-04-30	2	17	90.00	89.10	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
49	220	2026-04-30	2	18	400.00	396.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
50	221	2026-05-31	1	5	675.00	668.25	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
51	222	2026-05-31	1	1	80.00	79.20	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
52	223	2026-05-31	1	1	80.00	79.20	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
54	225	2026-05-31	4	19	400.00	396.00	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
55	226	2026-05-31	2	20	650.00	643.50	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
56	227	2026-05-31	2	21	1080.00	1069.20	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
57	228	2026-06-30	2	8	230.00	230.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
58	229	2026-06-30	1	5	477.50	472.73	PAGADA	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
59	231	2026-06-30	2	22	600.00	594.00	PAGADA	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
60	236	2026-06-30	1	1	462.00	457.38	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
61	238	2026-06-30	4	25	220.00	220.00	PAGADA	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:23	\N
53	224	2026-05-31	2	26	0.00	0.00	anulado	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:47:37	2026-07-09 20:47:37
33	180	2026-01-12	1	1	165.00	163.35	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
34	181	2026-01-13	2	2	250.00	247.50	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
35	182	2026-01-13	2	3	550.00	544.50	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
36	183	2026-01-16	2	4	300.00	300.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
37	184	2026-01-16	1	1	400.00	396.00	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
38	185	2026-01-17	2	4	75.00	75.00	pendiente	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
39	186	2026-01-19	1	5	475.50	470.75	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
40	187	2026-01-19	1	5	155.00	153.45	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
41	188	2026-01-20	1	1	440.00	435.60	PAGADO	\N	1	\N	2026-07-09 20:47:23	2026-07-09 20:48:08	2026-07-09 20:48:08
\.


--
-- Data for Name: failed_jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.failed_jobs (id, uuid, connection, queue, payload, exception, failed_at) FROM stdin;
\.


--
-- Data for Name: job_batches; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.job_batches (id, name, total_jobs, pending_jobs, failed_jobs, failed_job_ids, options, cancelled_at, created_at, finished_at) FROM stdin;
\.


--
-- Data for Name: jobs; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.jobs (id, queue, payload, attempts, reserved_at, available_at, created_at) FROM stdin;
\.


--
-- Data for Name: liquidacion_detalles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.liquidacion_detalles (id, liquidacion_id, factura_id, importe_aplicado, observacion, created_at, updated_at) FROM stdin;
1	1	2	247.50	\N	2026-07-09 19:41:57	2026-07-09 19:41:57
2	1	3	544.50	\N	2026-07-09 19:41:57	2026-07-09 19:41:57
3	1	4	300.00	\N	2026-07-09 19:41:57	2026-07-09 19:41:57
4	1	6	75.00	\N	2026-07-09 19:41:57	2026-07-09 19:41:57
\.


--
-- Data for Name: liquidaciones; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.liquidaciones (id, socio_id, periodo_mes, periodo_anio, total_facturado, total_retenciones, total_distribuciones, total_neto, estado, firma_socio, fecha_generacion, created_by, created_at, updated_at) FROM stdin;
1	2	1	2026	1175.00	35.01	149.78	990.21	borrador	\N	2026-07-09	1	2026-07-09 19:41:57	2026-07-09 19:41:57
\.


--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2025_01_01_200001_create_roles_table	1
5	2025_01_01_200002_create_permissions_table	1
6	2025_01_01_200003_create_role_user_table	1
7	2025_01_01_200004_create_permission_role_table	1
8	2025_01_01_200005_add_security_fields_to_users_table	1
9	2025_01_01_200006_create_socios_table	1
10	2025_01_01_200007_create_clientes_table	1
11	2025_01_01_200008_create_categorias_movimiento_table	1
12	2025_01_01_200009_create_tipos_retencion_table	1
13	2025_01_01_200010_create_facturas_table	1
14	2025_01_01_200011_create_factura_retenciones_table	1
15	2025_01_01_200012_create_factura_distribuciones_table	1
16	2025_01_01_200013_create_liquidaciones_table	1
17	2025_01_01_200014_create_liquidacion_detalles_table	1
18	2025_01_01_200015_create_movimientos_caja_table	1
19	2025_01_01_200016_create_aportes_socios_table	1
20	2025_01_01_200018_create_archivos_adjuntos_table	1
21	2025_01_01_200019_create_audit_logs_table	1
22	2025_01_01_200020_add_socio_id_to_users_table	1
23	2025_06_16_200022_remove_nombre_from_factura_retenciones_table	1
24	2026_06_17_012239_add_base_calculo_to_factura_distribuciones_table	1
\.


--
-- Data for Name: movimientos_caja; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.movimientos_caja (id, fecha, tipo, categoria_id, descripcion, valor, referencia_tipo, referencia_id, estado, created_by, created_at, updated_at) FROM stdin;
2	2026-01-31	egreso	7	PAGO DE IESS	210.33	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
3	2026-01-31	egreso	5	PAGO DE DIFERENCIA AL CONTADOR	222.19	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
5	2026-01-31	egreso	4	EMISION DE FACTURAS	35.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
6	2026-01-31	egreso	1	PAGO A CONTADOR-ENERO	165.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
7	2026-01-31	egreso	2	MOVILIZACION	20.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
10	2026-02-28	egreso	9	Renovacion de factura	96.03	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
11	2026-02-28	egreso	10	Aporte de la Agencia de transito	200.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
12	2026-02-28	egreso	6	Chequera	6.20	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
13	2026-02-28	egreso	2	Movilizacion	20.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
14	2026-02-28	egreso	4	Pago de emision de facturas	35.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
15	2026-02-28	egreso	1	Pago al contador-Febrero	165.00	\N	\N	activo	1	2026-06-30 19:22:54	2026-06-30 19:22:54
4	2026-01-31	egreso	8	PAGO DE PROTESTA CHEQUES	190.27	\N	\N	activo	1	2026-06-30 19:22:54	2026-07-09 17:19:16
16	2026-02-28	egreso	3	Servicios Bancarios	1.23	\N	\N	activo	1	2026-06-30 19:22:54	2026-07-09 18:31:18
18	2026-03-31	egreso	15	Pago a la Marea por convocatoria ordinaria	38.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
19	2026-03-31	egreso	16	Permiso de funcionamiento de Bomberos	82.35	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
20	2026-03-31	egreso	17	Pago por Informe de Comisario (R)	50.75	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
21	2026-03-31	egreso	4	Emision de facturas	35.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
22	2026-03-31	egreso	1	Pago al Contador-Marzo	165.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
23	2026-03-31	egreso	2	Movilizacion	20.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
24	2026-03-31	egreso	3	Servicios Bancarios	2.05	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
25	2026-04-30	egreso	4	Emision de facturas	35.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
26	2026-04-30	egreso	18	PAGO DE DECLARACION DE IMPUESTOS	162.32	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
27	2026-04-30	egreso	19	PAGO DE DECLARACION DE IVA	5.25	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
28	2026-04-30	egreso	20	PAGO DE DECLARACION DE RETENCION	1.50	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
29	2026-04-30	egreso	1	PAGO AL CONTADOR-ABRIL	165.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
30	2026-04-30	egreso	2	Movilizacion	20.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
31	2026-04-30	egreso	26	Certificado Bancario	2.59	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
32	2026-04-30	egreso	3	Servicios Bancarios	2.98	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
33	2026-05-31	egreso	21	PAGO DE RETENCION DE FEBRERO 2023	19.54	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
34	2026-05-31	egreso	21	PAGO DE RETENCION DE MAYO 2024	11.39	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
35	2026-05-31	egreso	22	OBLIGACION DE SUPER DE COMPAÑIAS	89.66	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
36	2026-05-31	egreso	1	PAGO AL CONTADOR - MES DE MAYO	165.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
37	2026-05-31	egreso	4	EMISION DE FACTURAS	35.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
38	2026-05-31	egreso	2	MOVILIZACION	20.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
39	2026-05-31	egreso	3	SERVICIOS BANCARIOS	2.16	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
40	2026-06-30	egreso	23	TRANMITE DE PATENTES Y LICENCIAS	25.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
41	2026-06-30	egreso	1	PAGO AL CONTADOR-MES DE JUNIO	165.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
42	2026-06-30	egreso	24	HOJAS Y TINTA PARA DOCUMENTOS ANT	5.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
43	2026-06-30	egreso	25	PAGO PARA TRANMITE DE BOMBEROS	20.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
44	2026-06-30	egreso	4	EMISION DE FACTURAS	35.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
45	2026-06-30	egreso	2	MOVILIZACION	20.00	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
46	2026-06-30	egreso	3	SERVICIOS BANCARIOS	2.46	\N	\N	activo	1	2026-07-09 18:46:54	2026-07-09 18:46:54
8	2026-01-31	egreso	3	SERVICIOS BANCARIOS	3.21	\N	\N	activo	1	2026-06-30 19:22:54	2026-07-09 20:32:10
47	2026-01-31	ingreso	12	VALOR RECIBIDO FACTURAS - ENERO	1220.85	\N	\N	activo	1	2026-07-09 20:47:23	2026-07-09 20:47:23
63	2026-04-30	ingreso	12	VALOR RECIBIDO FACTURAS - ABRIL	1140.24	\N	\N	activo	1	2026-07-09 20:47:23	2026-07-09 20:47:23
64	2026-05-31	ingreso	12	VALOR RECIBIDO FACTURAS - MAYO	1290.53	\N	\N	activo	1	2026-07-09 20:47:23	2026-07-09 20:47:23
65	2026-06-30	ingreso	12	VALOR RECIBIDO FACTURAS - JUNIO	1485.84	\N	\N	activo	1	2026-07-09 20:47:23	2026-07-09 20:47:23
\.


--
-- Data for Name: password_reset_tokens; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.password_reset_tokens (email, token, created_at) FROM stdin;
\.


--
-- Data for Name: permission_role; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.permission_role (permission_id, role_id) FROM stdin;
\.


--
-- Data for Name: permissions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.permissions (id, nombre, slug, descripcion, activo, created_at, updated_at) FROM stdin;
1	Ver usuarios	ver-usuarios	Ver listado de usuarios	t	2026-06-30 19:22:53	2026-06-30 19:22:53
2	Crear usuarios	crear-usuarios	Crear nuevos usuarios	t	2026-06-30 19:22:53	2026-06-30 19:22:53
3	Editar usuarios	editar-usuarios	Editar usuarios existentes	t	2026-06-30 19:22:53	2026-06-30 19:22:53
4	Eliminar usuarios	eliminar-usuarios	Eliminar usuarios	t	2026-06-30 19:22:53	2026-06-30 19:22:53
5	Ver socios	ver-socios	Ver listado de socios	t	2026-06-30 19:22:53	2026-06-30 19:22:53
6	Crear socios	crear-socios	Crear nuevos socios	t	2026-06-30 19:22:53	2026-06-30 19:22:53
7	Editar socios	editar-socios	Editar socios existentes	t	2026-06-30 19:22:53	2026-06-30 19:22:53
8	Ver clientes	ver-clientes	Ver listado de clientes	t	2026-06-30 19:22:53	2026-06-30 19:22:53
9	Crear clientes	crear-clientes	Crear nuevos clientes	t	2026-06-30 19:22:53	2026-06-30 19:22:53
10	Editar clientes	editar-clientes	Editar clientes existentes	t	2026-06-30 19:22:53	2026-06-30 19:22:53
11	Ver facturas	ver-facturas	Ver listado de facturas	t	2026-06-30 19:22:53	2026-06-30 19:22:53
12	Crear facturas	crear-facturas	Crear nuevas facturas	t	2026-06-30 19:22:53	2026-06-30 19:22:53
13	Editar facturas	editar-facturas	Editar facturas	t	2026-06-30 19:22:53	2026-06-30 19:22:53
14	Anular facturas	anular-facturas	Anular facturas	t	2026-06-30 19:22:53	2026-06-30 19:22:53
15	Ver liquidaciones	ver-liquidaciones	Ver liquidaciones	t	2026-06-30 19:22:53	2026-06-30 19:22:53
16	Crear liquidaciones	crear-liquidaciones	Generar liquidaciones	t	2026-06-30 19:22:53	2026-06-30 19:22:53
17	Aprobar liquidaciones	aprobar-liquidaciones	Aprobar liquidaciones	t	2026-06-30 19:22:53	2026-06-30 19:22:53
18	Ver movimientos	ver-movimientos	Ver movimientos de caja	t	2026-06-30 19:22:53	2026-06-30 19:22:53
19	Crear movimientos	crear-movimientos	Registrar movimientos de caja	t	2026-06-30 19:22:53	2026-06-30 19:22:53
20	Cerrar caja	cerrar-caja	Realizar cierres mensuales	t	2026-06-30 19:22:53	2026-06-30 19:22:53
21	Ver reportes	ver-reportes	Ver reportes y exportar	t	2026-06-30 19:22:53	2026-06-30 19:22:53
\.


--
-- Data for Name: role_user; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.role_user (role_id, user_id) FROM stdin;
1	1
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.roles (id, nombre, slug, descripcion, activo, created_at, updated_at) FROM stdin;
1	Super Administrador	super-admin	Acceso total al sistema	t	2026-06-30 19:22:53	2026-06-30 19:22:53
2	Administrador	admin	Gestiona usuarios, facturas y liquidaciones	t	2026-06-30 19:22:53	2026-06-30 19:22:53
3	Socio	socio	Ve sus propias facturas y liquidaciones	t	2026-06-30 19:22:53	2026-06-30 19:22:53
4	Contador	contador	Acceso a caja, gastos y reportes contables	t	2026-06-30 19:22:53	2026-06-30 19:22:53
\.


--
-- Data for Name: sessions; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.sessions (id, user_id, ip_address, user_agent, payload, last_activity) FROM stdin;
fBCblag7LXvjc6d80Rliz2Ny0tEGA7NwycfpZVvc	\N	127.0.0.1	Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36	eyJfdG9rZW4iOiI1Vk1QNGE1dDNScGY0NnF1Y1l0M1hwYkNpT0Z1a3dUOXM3ZFkzanozIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAiLCJyb3V0ZSI6bnVsbH19	1783631121
\.


--
-- Data for Name: socios; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.socios (id, nombres, identificacion, telefono, email, direccion, cuota_mensual_base, porcentaje_participacion, tipo_socio, activo, fecha_registro, created_at, updated_at, deleted_at) FROM stdin;
1	Nelson Jimenez	NELSON001	\N	\N	\N	75.00	\N	socio	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
2	Freddy Delgado	FREDDY001	\N	\N	\N	150.00	\N	socio	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
3	Gustavo Bunay	GUSTAVO001	\N	\N	\N	75.00	\N	socio	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
4	Letty's Williams	LETTY001	\N	\N	\N	75.00	\N	socio	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
5	Eliana Mero	ELIANA001	\N	\N	\N	0.00	\N	colaborador	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
6	Rogelio Molina	ROGELIO001	\N	\N	\N	75.00	\N	socio	t	2026-01-01 00:00:00	2026-06-30 19:22:54	2026-06-30 19:22:54	\N
\.


--
-- Data for Name: tipos_retencion; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.tipos_retencion (id, nombre, slug, porcentaje, descripcion, activo, created_at, updated_at) FROM stdin;
1	Retencion 1%	retencion-1	1.00	Retencion del 1% sobre valor de factura	t	2026-06-30 19:22:54	2026-06-30 19:22:54
2	Retencion Turismo 3%	retencion-turismo-3	3.00	Retencion de turismo del 3% sobre valor recibido	t	2026-06-30 19:22:54	2026-06-30 19:22:54
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.users (id, name, email, email_verified_at, password, remember_token, created_at, updated_at, role_id, activo, socio_id) FROM stdin;
1	Administrador	admin@admin.com	\N	$2y$12$zSNPm0YM4U/feIbeanUTO.p4rdHcn3kMG/PhztgZjojZdci5iP0ze	\N	2026-06-30 19:22:54	2026-06-30 19:22:54	1	t	\N
\.


--
-- Name: aportes_socios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.aportes_socios_id_seq', 50, true);


--
-- Name: archivos_adjuntos_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.archivos_adjuntos_id_seq', 1, false);


--
-- Name: audit_logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.audit_logs_id_seq', 1, false);


--
-- Name: categorias_movimiento_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.categorias_movimiento_id_seq', 26, true);


--
-- Name: clientes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.clientes_id_seq', 26, true);


--
-- Name: factura_distribuciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.factura_distribuciones_id_seq', 72, true);


--
-- Name: factura_retenciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.factura_retenciones_id_seq', 61, true);


--
-- Name: facturas_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.facturas_id_seq', 61, true);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.failed_jobs_id_seq', 1, false);


--
-- Name: jobs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.jobs_id_seq', 1, false);


--
-- Name: liquidacion_detalles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.liquidacion_detalles_id_seq', 4, true);


--
-- Name: liquidaciones_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.liquidaciones_id_seq', 1, true);


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 24, true);


--
-- Name: movimientos_caja_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.movimientos_caja_id_seq', 65, true);


--
-- Name: permissions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.permissions_id_seq', 21, true);


--
-- Name: roles_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.roles_id_seq', 4, true);


--
-- Name: socios_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.socios_id_seq', 6, true);


--
-- Name: tipos_retencion_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.tipos_retencion_id_seq', 2, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.users_id_seq', 1, true);


--
-- Name: aportes_socios aportes_socios_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aportes_socios
    ADD CONSTRAINT aportes_socios_pkey PRIMARY KEY (id);


--
-- Name: archivos_adjuntos archivos_adjuntos_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.archivos_adjuntos
    ADD CONSTRAINT archivos_adjuntos_pkey PRIMARY KEY (id);


--
-- Name: audit_logs audit_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: categorias_movimiento categorias_movimiento_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.categorias_movimiento
    ADD CONSTRAINT categorias_movimiento_pkey PRIMARY KEY (id);


--
-- Name: clientes clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id);


--
-- Name: clientes clientes_ruc_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_ruc_unique UNIQUE (ruc);


--
-- Name: factura_distribuciones factura_distribuciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_distribuciones
    ADD CONSTRAINT factura_distribuciones_pkey PRIMARY KEY (id);


--
-- Name: factura_retenciones factura_retenciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_retenciones
    ADD CONSTRAINT factura_retenciones_pkey PRIMARY KEY (id);


--
-- Name: facturas facturas_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT facturas_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: liquidacion_detalles liquidacion_detalles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidacion_detalles
    ADD CONSTRAINT liquidacion_detalles_pkey PRIMARY KEY (id);


--
-- Name: liquidaciones liquidaciones_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidaciones
    ADD CONSTRAINT liquidaciones_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: movimientos_caja movimientos_caja_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.movimientos_caja
    ADD CONSTRAINT movimientos_caja_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: permission_role permission_role_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permission_role
    ADD CONSTRAINT permission_role_pkey PRIMARY KEY (permission_id, role_id);


--
-- Name: permissions permissions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_pkey PRIMARY KEY (id);


--
-- Name: permissions permissions_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permissions
    ADD CONSTRAINT permissions_slug_unique UNIQUE (slug);


--
-- Name: role_user role_user_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_pkey PRIMARY KEY (role_id, user_id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (id);


--
-- Name: roles roles_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_slug_unique UNIQUE (slug);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: socios socios_identificacion_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.socios
    ADD CONSTRAINT socios_identificacion_unique UNIQUE (identificacion);


--
-- Name: socios socios_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.socios
    ADD CONSTRAINT socios_pkey PRIMARY KEY (id);


--
-- Name: tipos_retencion tipos_retencion_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipos_retencion
    ADD CONSTRAINT tipos_retencion_pkey PRIMARY KEY (id);


--
-- Name: tipos_retencion tipos_retencion_slug_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.tipos_retencion
    ADD CONSTRAINT tipos_retencion_slug_unique UNIQUE (slug);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: archivos_adjuntos_archivable_type_archivable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX archivos_adjuntos_archivable_type_archivable_id_index ON public.archivos_adjuntos USING btree (archivable_type, archivable_id);


--
-- Name: audit_logs_auditable_type_auditable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX audit_logs_auditable_type_auditable_id_index ON public.audit_logs USING btree (auditable_type, auditable_id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: aportes_socios aportes_socios_socio_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.aportes_socios
    ADD CONSTRAINT aportes_socios_socio_id_foreign FOREIGN KEY (socio_id) REFERENCES public.socios(id) ON DELETE CASCADE;


--
-- Name: audit_logs audit_logs_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.audit_logs
    ADD CONSTRAINT audit_logs_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: factura_distribuciones factura_distribuciones_factura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_distribuciones
    ADD CONSTRAINT factura_distribuciones_factura_id_foreign FOREIGN KEY (factura_id) REFERENCES public.facturas(id) ON DELETE CASCADE;


--
-- Name: factura_distribuciones factura_distribuciones_socio_destino_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_distribuciones
    ADD CONSTRAINT factura_distribuciones_socio_destino_id_foreign FOREIGN KEY (socio_destino_id) REFERENCES public.socios(id) ON DELETE RESTRICT;


--
-- Name: factura_retenciones factura_retenciones_factura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_retenciones
    ADD CONSTRAINT factura_retenciones_factura_id_foreign FOREIGN KEY (factura_id) REFERENCES public.facturas(id) ON DELETE CASCADE;


--
-- Name: factura_retenciones factura_retenciones_tipo_retencion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.factura_retenciones
    ADD CONSTRAINT factura_retenciones_tipo_retencion_id_foreign FOREIGN KEY (tipo_retencion_id) REFERENCES public.tipos_retencion(id) ON DELETE RESTRICT;


--
-- Name: facturas facturas_cliente_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT facturas_cliente_id_foreign FOREIGN KEY (cliente_id) REFERENCES public.clientes(id) ON DELETE RESTRICT;


--
-- Name: facturas facturas_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT facturas_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: facturas facturas_socio_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT facturas_socio_id_foreign FOREIGN KEY (socio_id) REFERENCES public.socios(id) ON DELETE RESTRICT;


--
-- Name: facturas facturas_updated_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT facturas_updated_by_foreign FOREIGN KEY (updated_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: liquidacion_detalles liquidacion_detalles_factura_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidacion_detalles
    ADD CONSTRAINT liquidacion_detalles_factura_id_foreign FOREIGN KEY (factura_id) REFERENCES public.facturas(id) ON DELETE RESTRICT;


--
-- Name: liquidacion_detalles liquidacion_detalles_liquidacion_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidacion_detalles
    ADD CONSTRAINT liquidacion_detalles_liquidacion_id_foreign FOREIGN KEY (liquidacion_id) REFERENCES public.liquidaciones(id) ON DELETE CASCADE;


--
-- Name: liquidaciones liquidaciones_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidaciones
    ADD CONSTRAINT liquidaciones_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: liquidaciones liquidaciones_socio_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.liquidaciones
    ADD CONSTRAINT liquidaciones_socio_id_foreign FOREIGN KEY (socio_id) REFERENCES public.socios(id) ON DELETE RESTRICT;


--
-- Name: movimientos_caja movimientos_caja_categoria_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.movimientos_caja
    ADD CONSTRAINT movimientos_caja_categoria_id_foreign FOREIGN KEY (categoria_id) REFERENCES public.categorias_movimiento(id) ON DELETE RESTRICT;


--
-- Name: movimientos_caja movimientos_caja_created_by_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.movimientos_caja
    ADD CONSTRAINT movimientos_caja_created_by_foreign FOREIGN KEY (created_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: permission_role permission_role_permission_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permission_role
    ADD CONSTRAINT permission_role_permission_id_foreign FOREIGN KEY (permission_id) REFERENCES public.permissions(id) ON DELETE CASCADE;


--
-- Name: permission_role permission_role_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.permission_role
    ADD CONSTRAINT permission_role_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_user role_user_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE CASCADE;


--
-- Name: role_user role_user_user_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.role_user
    ADD CONSTRAINT role_user_user_id_foreign FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: users users_role_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_role_id_foreign FOREIGN KEY (role_id) REFERENCES public.roles(id) ON DELETE SET NULL;


--
-- Name: users users_socio_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_socio_id_foreign FOREIGN KEY (socio_id) REFERENCES public.socios(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict SzmYzWRB6jxwkQ4VKzS44e8nxFY7FOs9QSyDolNeHc2qIlL3McNGFJhchDHXOMd

