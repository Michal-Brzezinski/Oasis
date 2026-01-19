--
-- PostgreSQL database dump
--

\restrict Vf2omLDi31uC1Qjz20Wpxcm1l0T0CvloibeNM9RbvSc7czKBRXWibWCRe31wvCn

-- Dumped from database version 18.1 (Debian 18.1-1.pgdg13+2)
-- Dumped by pg_dump version 18.1 (Debian 18.1-1.pgdg13+2)

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

--
-- Name: generate_crop_identifier(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.generate_crop_identifier() RETURNS character varying
    LANGUAGE plpgsql
    AS $$
DECLARE
    chars TEXT := 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'; -- bez mylących znaków
    result VARCHAR(10) := '';
    i INTEGER;
    identifier_exists BOOLEAN;
BEGIN
    LOOP
        result := '';
        FOR i IN 1..10 LOOP
            result := result || substr(chars, floor(random() * length(chars) + 1)::integer, 1);
        END LOOP;
        
        -- Sprawdź czy już istnieje
        SELECT EXISTS(SELECT 1 FROM crops WHERE identifier = result) INTO identifier_exists;
        
        EXIT WHEN NOT identifier_exists;
    END LOOP;
    
    RETURN result;
END;
$$;


ALTER FUNCTION public.generate_crop_identifier() OWNER TO docker;

--
-- Name: update_updated_at_column(); Type: FUNCTION; Schema: public; Owner: docker
--

CREATE FUNCTION public.update_updated_at_column() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$;


ALTER FUNCTION public.update_updated_at_column() OWNER TO docker;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cameras; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.cameras (
    id integer NOT NULL,
    region_id integer NOT NULL,
    name character varying(100) NOT NULL,
    stream_url text NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.cameras OWNER TO docker;

--
-- Name: cameras_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.cameras_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.cameras_id_seq OWNER TO docker;

--
-- Name: cameras_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.cameras_id_seq OWNED BY public.cameras.id;


--
-- Name: login_attempts; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.login_attempts (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    ip_address character varying(45),
    attempted_at timestamp without time zone DEFAULT now() NOT NULL,
    success boolean NOT NULL
);


ALTER TABLE public.login_attempts OWNER TO docker;

--
-- Name: login_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.login_attempts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.login_attempts_id_seq OWNER TO docker;

--
-- Name: login_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.login_attempts_id_seq OWNED BY public.login_attempts.id;


--
-- Name: notifications; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.notifications (
    id integer NOT NULL,
    user_id integer NOT NULL,
    message text NOT NULL,
    created_at timestamp without time zone DEFAULT now(),
    is_read boolean DEFAULT false
);


ALTER TABLE public.notifications OWNER TO docker;

--
-- Name: notifications_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.notifications_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.notifications_id_seq OWNER TO docker;

--
-- Name: notifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.notifications_id_seq OWNED BY public.notifications.id;


--
-- Name: regions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.regions (
    id integer NOT NULL,
    owner_id integer NOT NULL,
    name character varying(100) NOT NULL,
    description text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.regions OWNER TO docker;

--
-- Name: regions_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.regions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.regions_id_seq OWNER TO docker;

--
-- Name: regions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.regions_id_seq OWNED BY public.regions.id;


--
-- Name: schedules; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.schedules (
    id integer NOT NULL,
    region_id integer NOT NULL,
    name character varying(100) NOT NULL,
    cron_expression character varying(100) NOT NULL,
    volume_liters numeric(10,2) NOT NULL,
    is_enabled boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.schedules OWNER TO docker;

--
-- Name: schedules_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.schedules_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.schedules_id_seq OWNER TO docker;

--
-- Name: schedules_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.schedules_id_seq OWNED BY public.schedules.id;


--
-- Name: sensor_readings; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.sensor_readings (
    id integer NOT NULL,
    sensor_id integer NOT NULL,
    value double precision NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);


ALTER TABLE public.sensor_readings OWNER TO docker;

--
-- Name: sensor_readings_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.sensor_readings_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sensor_readings_id_seq OWNER TO docker;

--
-- Name: sensor_readings_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.sensor_readings_id_seq OWNED BY public.sensor_readings.id;


--
-- Name: sensors; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.sensors (
    id integer NOT NULL,
    region_id integer NOT NULL,
    name character varying(100) NOT NULL,
    type character varying(50) NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.sensors OWNER TO docker;

--
-- Name: sensors_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.sensors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.sensors_id_seq OWNER TO docker;

--
-- Name: sensors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.sensors_id_seq OWNED BY public.sensors.id;


--
-- Name: user_preferences; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.user_preferences (
    id integer NOT NULL,
    user_id integer NOT NULL,
    email_notifications_enabled boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.user_preferences OWNER TO docker;

--
-- Name: user_preferences_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.user_preferences_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_preferences_id_seq OWNER TO docker;

--
-- Name: user_preferences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.user_preferences_id_seq OWNED BY public.user_preferences.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.users (
    id integer NOT NULL,
    email character varying(255) NOT NULL,
    password_hash character varying(255) NOT NULL,
    nickname character varying(100) NOT NULL,
    full_name character varying(150) NOT NULL,
    role character varying(20) DEFAULT 'OWNER'::character varying NOT NULL,
    is_active boolean DEFAULT true NOT NULL,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    updated_at timestamp without time zone
);


ALTER TABLE public.users OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_id_seq OWNER TO docker;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: watering_actions; Type: TABLE; Schema: public; Owner: docker
--

CREATE TABLE public.watering_actions (
    id integer NOT NULL,
    region_id integer NOT NULL,
    schedule_id integer,
    initiated_by integer,
    started_at timestamp without time zone DEFAULT now() NOT NULL,
    stopped_at timestamp without time zone,
    status character varying(20) DEFAULT 'PENDING'::character varying NOT NULL,
    volume_liters numeric(10,2)
);


ALTER TABLE public.watering_actions OWNER TO docker;

--
-- Name: watering_actions_id_seq; Type: SEQUENCE; Schema: public; Owner: docker
--

CREATE SEQUENCE public.watering_actions_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.watering_actions_id_seq OWNER TO docker;

--
-- Name: watering_actions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: docker
--

ALTER SEQUENCE public.watering_actions_id_seq OWNED BY public.watering_actions.id;


--
-- Name: cameras id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cameras ALTER COLUMN id SET DEFAULT nextval('public.cameras_id_seq'::regclass);


--
-- Name: login_attempts id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.login_attempts ALTER COLUMN id SET DEFAULT nextval('public.login_attempts_id_seq'::regclass);


--
-- Name: notifications id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.notifications ALTER COLUMN id SET DEFAULT nextval('public.notifications_id_seq'::regclass);


--
-- Name: regions id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.regions ALTER COLUMN id SET DEFAULT nextval('public.regions_id_seq'::regclass);


--
-- Name: schedules id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.schedules ALTER COLUMN id SET DEFAULT nextval('public.schedules_id_seq'::regclass);


--
-- Name: sensor_readings id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.sensor_readings ALTER COLUMN id SET DEFAULT nextval('public.sensor_readings_id_seq'::regclass);


--
-- Name: sensors id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.sensors ALTER COLUMN id SET DEFAULT nextval('public.sensors_id_seq'::regclass);


--
-- Name: user_preferences id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_preferences ALTER COLUMN id SET DEFAULT nextval('public.user_preferences_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: watering_actions id; Type: DEFAULT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.watering_actions ALTER COLUMN id SET DEFAULT nextval('public.watering_actions_id_seq'::regclass);


--
-- Data for Name: cameras; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.cameras (id, region_id, name, stream_url, is_active, created_at, updated_at) FROM stdin;
1	1	Kamera szklarnia 1	rtsp://example.com/greenhouse1	t	2026-01-17 00:00:01.772732	\N
2	2	Kamera trawnik 1	rtsp://example.com/lawn1	t	2026-01-17 00:00:01.772732	\N
\.


--
-- Data for Name: login_attempts; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.login_attempts (id, email, ip_address, attempted_at, success) FROM stdin;
\.


--
-- Data for Name: notifications; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.notifications (id, user_id, message, created_at, is_read) FROM stdin;
1	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 29.5%	2026-01-18 18:52:50.154327	f
2	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 29.1%	2026-01-18 18:52:54.651621	f
3	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 28.9%	2026-01-18 18:53:02.869849	f
4	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 28.8%	2026-01-18 18:53:07.092126	f
5	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 29.6%	2026-01-18 18:53:11.508604	f
6	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 28.6%	2026-01-18 18:53:11.517622	f
7	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 29.9%	2026-01-18 18:53:16.003316	f
8	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 29.1%	2026-01-18 18:53:16.012931	f
9	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 28.5%	2026-01-18 18:53:16.02495	f
10	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 29.4%	2026-01-18 18:53:20.469695	f
11	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 28.9%	2026-01-18 18:53:20.478644	f
12	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 28.0%	2026-01-18 18:53:20.487407	f
13	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 29.3%	2026-01-18 18:53:25.647028	f
14	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 28.6%	2026-01-18 18:53:25.661274	f
15	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 27.6%	2026-01-18 18:53:25.670553	f
17	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 29.0%	2026-01-18 18:53:29.517699	f
18	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 28.3%	2026-01-18 18:53:29.530082	f
19	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 27.2%	2026-01-18 18:53:29.540445	f
21	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 28.6%	2026-01-18 18:53:37.338747	f
22	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 28.0%	2026-01-18 18:53:37.347872	f
23	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 27.0%	2026-01-18 18:53:37.357507	f
25	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 28.2%	2026-01-18 18:53:41.84098	f
26	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 27.9%	2026-01-18 18:53:41.849809	f
27	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 26.7%	2026-01-18 18:53:41.860772	f
29	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 27.8%	2026-01-18 18:53:46.335436	f
30	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 27.8%	2026-01-18 18:53:46.345766	f
31	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 26.2%	2026-01-18 18:53:46.355692	f
33	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 27.5%	2026-01-18 18:53:51.350928	f
34	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 27.5%	2026-01-18 18:53:51.426527	f
35	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 25.7%	2026-01-18 18:53:51.481604	f
37	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 27.1%	2026-01-18 18:53:55.947282	f
38	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 27.1%	2026-01-18 18:53:55.956149	f
39	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 25.2%	2026-01-18 18:53:55.967108	f
41	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 26.8%	2026-01-18 18:53:59.843905	f
42	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 26.6%	2026-01-18 18:53:59.852464	f
43	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 25.1%	2026-01-18 18:53:59.863659	f
45	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 26.4%	2026-01-18 18:54:07.606284	f
46	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 26.3%	2026-01-18 18:54:07.616852	f
47	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 24.6%	2026-01-18 18:54:07.62576	f
16	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 29.8%	2026-01-18 18:53:25.680439	t
20	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 29.5%	2026-01-18 18:53:29.558847	t
24	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 29.4%	2026-01-18 18:53:37.366806	t
28	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 29.1%	2026-01-18 18:53:41.868392	t
32	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 28.9%	2026-01-18 18:53:46.369378	t
36	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 28.7%	2026-01-18 18:53:51.494578	t
40	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 28.2%	2026-01-18 18:53:55.975348	t
44	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 28.0%	2026-01-18 18:53:59.87362	t
49	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 26.3%	2026-01-18 19:35:40.495172	f
50	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 25.9%	2026-01-18 19:35:40.506964	f
51	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 24.3%	2026-01-18 19:35:40.51551	f
53	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 25.8%	2026-01-18 19:35:45.446171	f
54	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 25.4%	2026-01-18 19:35:45.453247	f
55	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 24.0%	2026-01-18 19:35:45.461009	f
57	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 25.5%	2026-01-18 19:35:53.996634	f
58	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 24.9%	2026-01-18 19:35:54.011945	f
59	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 23.6%	2026-01-18 19:35:54.025851	f
61	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 25.1%	2026-01-18 19:36:08.872411	f
62	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 24.7%	2026-01-18 19:36:08.879877	f
63	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 23.3%	2026-01-18 19:36:08.88576	f
65	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 24.9%	2026-01-18 19:36:15.493274	f
66	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 24.4%	2026-01-18 19:36:15.502823	f
67	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 22.9%	2026-01-18 19:36:15.5101	f
69	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 24.4%	2026-01-18 19:36:20.470811	f
70	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 24.3%	2026-01-18 19:36:20.480614	f
71	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 22.8%	2026-01-18 19:36:20.487435	f
73	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik wilgotności gleby 1): 24.3%	2026-01-18 19:36:25.414892	f
74	1	Niska wilgotność w regionie "Szklarnia" (czujnik: Czujnik temperatury powietrza): 24.0%	2026-01-18 19:36:25.422816	f
75	1	Niska wilgotność w regionie "Trawnik" (czujnik: Czujnik nasłonecznienia): 22.4%	2026-01-18 19:36:25.431558	f
48	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 27.9%	2026-01-18 18:54:07.634438	t
52	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 27.5%	2026-01-18 19:35:40.522819	t
56	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 27.2%	2026-01-18 19:35:45.468419	t
60	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 27.0%	2026-01-18 19:35:54.034616	t
64	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 26.8%	2026-01-18 19:36:08.891753	t
68	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 26.7%	2026-01-18 19:36:15.520127	t
72	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 26.4%	2026-01-18 19:36:20.49468	t
76	4	Niska wilgotność w regionie "Ogród warzywny" (czujnik: Cytron Maker Soil Moisture1): 25.9%	2026-01-18 19:36:25.439697	t
\.


--
-- Data for Name: regions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.regions (id, owner_id, name, description, created_at, updated_at) FROM stdin;
1	1	Szklarnia	Główna szklarnia z pomidorami	2026-01-17 00:00:01.772732	\N
2	1	Trawnik	Trawnik przed domem	2026-01-17 00:00:01.772732	\N
5	4	Trawnik przed domem	Trawnik przy nowym domu	2026-01-17 20:32:32.911671	\N
3	4	Ogród warzywny	Mały ogród z warzywami	2026-01-17 20:32:32.911671	\N
7	4	Uprawa pomidorów	\N	2026-01-18 14:38:03.250779	\N
\.


--
-- Data for Name: schedules; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.schedules (id, region_id, name, cron_expression, volume_liters, is_enabled, created_at, updated_at) FROM stdin;
1	1	Poranne podlewanie szklarni	0 6 * * *	10.00	t	2026-01-17 00:00:01.772732	\N
2	2	Wieczorne podlewanie trawnika	0 20 * * *	15.00	t	2026-01-17 00:00:01.772732	\N
3	3	Podlewanie dni robocze	30 2 * * 1-5	150.00	t	2026-01-17 22:06:45.757458	2026-01-17 22:10:53.854583
\.


--
-- Data for Name: sensor_readings; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.sensor_readings (id, sensor_id, value, created_at) FROM stdin;
1	1	49.8	2026-01-18 17:27:42.33523
2	2	49.9	2026-01-18 17:27:42.359792
3	3	49.5	2026-01-18 17:27:42.362635
4	5	49.5	2026-01-18 17:27:42.366297
5	1	49.5	2026-01-18 18:05:24.747931
6	2	49.8	2026-01-18 18:05:24.780079
7	3	49.3	2026-01-18 18:05:24.785603
8	5	49	2026-01-18 18:05:24.791424
9	1	49	2026-01-18 18:05:49.471523
10	2	49.3	2026-01-18 18:05:49.477101
11	3	49	2026-01-18 18:05:49.480678
12	5	48.5	2026-01-18 18:05:49.484326
13	1	48.6	2026-01-18 18:05:54.37655
14	2	49.1	2026-01-18 18:05:54.386179
15	3	48.8	2026-01-18 18:05:54.39374
16	5	48	2026-01-18 18:05:54.406467
17	1	48.4	2026-01-18 18:05:58.580103
18	2	48.9	2026-01-18 18:05:58.586422
19	3	48.6	2026-01-18 18:05:58.592424
20	5	47.8	2026-01-18 18:05:58.598471
21	1	48.1	2026-01-18 18:06:05.874019
22	2	48.5	2026-01-18 18:06:05.879578
23	3	48.1	2026-01-18 18:06:05.883758
24	5	47.6	2026-01-18 18:06:05.887916
25	1	47.7	2026-01-18 18:46:10.157284
26	2	48	2026-01-18 18:46:10.190516
27	3	47.7	2026-01-18 18:46:10.194699
28	5	47.2	2026-01-18 18:46:10.198242
29	1	47.2	2026-01-18 18:46:13.896769
30	2	47.9	2026-01-18 18:46:13.902592
31	3	47.6	2026-01-18 18:46:13.90852
32	5	47.1	2026-01-18 18:46:13.911957
33	1	47	2026-01-18 18:46:21.623034
34	2	47.5	2026-01-18 18:46:21.627502
35	3	47.4	2026-01-18 18:46:21.632733
36	5	47	2026-01-18 18:46:21.636929
37	1	46.8	2026-01-18 18:46:26.156627
38	2	47.2	2026-01-18 18:46:26.161821
39	3	47.2	2026-01-18 18:46:26.166019
40	5	46.8	2026-01-18 18:46:26.170047
41	1	46.6	2026-01-18 18:46:30.615149
42	2	47.1	2026-01-18 18:46:30.620266
43	3	47	2026-01-18 18:46:30.625514
44	5	46.5	2026-01-18 18:46:30.63013
45	1	46.1	2026-01-18 18:46:35.384684
46	2	46.6	2026-01-18 18:46:35.393355
47	3	46.9	2026-01-18 18:46:35.397072
48	5	46	2026-01-18 18:46:35.40079
49	1	46	2026-01-18 18:46:39.621497
50	2	46.5	2026-01-18 18:46:39.626033
51	3	46.6	2026-01-18 18:46:39.629722
52	5	45.9	2026-01-18 18:46:39.633512
53	1	45.8	2026-01-18 18:46:44.112349
54	2	46.4	2026-01-18 18:46:44.117362
55	3	46.5	2026-01-18 18:46:44.122537
56	5	45.5	2026-01-18 18:46:44.126559
57	1	45.6	2026-01-18 18:46:48.592994
58	2	46.3	2026-01-18 18:46:48.599278
59	3	46.4	2026-01-18 18:46:48.603594
60	5	45	2026-01-18 18:46:48.607824
61	1	45.5	2026-01-18 18:46:56.491252
62	2	46	2026-01-18 18:46:56.496398
63	3	45.9	2026-01-18 18:46:56.500606
64	5	44.8	2026-01-18 18:46:56.505318
65	1	45.1	2026-01-18 18:47:00.978268
66	2	45.9	2026-01-18 18:47:00.984123
67	3	45.6	2026-01-18 18:47:00.989569
68	5	44.5	2026-01-18 18:47:00.994121
69	1	44.6	2026-01-18 18:47:05.500167
70	2	45.8	2026-01-18 18:47:05.50496
71	3	45.2	2026-01-18 18:47:05.509331
72	5	44.2	2026-01-18 18:47:05.513847
73	1	44.1	2026-01-18 18:48:25.26545
74	2	45.7	2026-01-18 18:48:25.274936
75	3	45	2026-01-18 18:48:25.279302
76	5	44.1	2026-01-18 18:48:25.283747
77	1	43.7	2026-01-18 18:48:33.109086
78	2	45.4	2026-01-18 18:48:33.114692
79	3	44.8	2026-01-18 18:48:33.119186
80	5	43.9	2026-01-18 18:48:33.123795
81	1	43.3	2026-01-18 18:48:38.047801
82	2	45.1	2026-01-18 18:48:38.052277
83	3	44.3	2026-01-18 18:48:38.056698
84	5	43.5	2026-01-18 18:48:38.061933
85	1	43	2026-01-18 18:48:42.13095
86	2	44.9	2026-01-18 18:48:42.138592
87	3	43.9	2026-01-18 18:48:42.143705
88	5	43.3	2026-01-18 18:48:42.149829
89	1	42.7	2026-01-18 18:48:46.590501
90	2	44.8	2026-01-18 18:48:46.595373
91	3	43.4	2026-01-18 18:48:46.59942
92	5	42.9	2026-01-18 18:48:46.604467
93	1	42.6	2026-01-18 18:48:51.086077
94	2	44.6	2026-01-18 18:48:51.09164
95	3	43	2026-01-18 18:48:51.0961
96	5	42.4	2026-01-18 18:48:51.101556
97	1	42.4	2026-01-18 18:48:55.580243
98	2	44.4	2026-01-18 18:48:55.58577
99	3	42.8	2026-01-18 18:48:55.590682
100	5	42.1	2026-01-18 18:48:55.59518
101	1	42	2026-01-18 18:49:00.192024
102	2	44.3	2026-01-18 18:49:00.197557
103	3	42.4	2026-01-18 18:49:00.201852
104	5	41.9	2026-01-18 18:49:00.205909
105	1	41.9	2026-01-18 18:49:07.904627
106	2	43.9	2026-01-18 18:49:07.910881
107	3	42.3	2026-01-18 18:49:07.915332
108	5	41.8	2026-01-18 18:49:07.91975
109	1	41.4	2026-01-18 18:49:13.441602
110	2	43.7	2026-01-18 18:49:13.504025
111	3	42.2	2026-01-18 18:49:13.569371
112	5	41.7	2026-01-18 18:49:13.599583
113	1	40.9	2026-01-18 18:49:16.942122
114	2	43.6	2026-01-18 18:49:16.947409
115	3	41.9	2026-01-18 18:49:16.951762
116	5	41.6	2026-01-18 18:49:16.955822
117	1	40.6	2026-01-18 18:49:21.412731
118	2	43.4	2026-01-18 18:49:21.416803
119	3	41.4	2026-01-18 18:49:21.420995
120	5	41.3	2026-01-18 18:49:21.425839
121	1	40.2	2026-01-18 18:49:25.901609
122	2	43.1	2026-01-18 18:49:25.907139
123	3	41.2	2026-01-18 18:49:25.910675
124	5	41	2026-01-18 18:49:25.915148
125	1	40.1	2026-01-18 18:49:30.40319
126	2	42.9	2026-01-18 18:49:30.411399
127	3	40.8	2026-01-18 18:49:30.415394
128	5	40.9	2026-01-18 18:49:30.41909
129	1	39.8	2026-01-18 18:49:35.8006
130	2	42.4	2026-01-18 18:49:35.821263
131	3	40.5	2026-01-18 18:49:35.847569
132	5	40.8	2026-01-18 18:49:35.859616
133	1	39.6	2026-01-18 18:49:42.830288
134	2	42	2026-01-18 18:49:42.837003
135	3	40.2	2026-01-18 18:49:42.841346
136	5	40.3	2026-01-18 18:49:42.846953
137	1	39.1	2026-01-18 18:49:47.263396
138	2	41.6	2026-01-18 18:49:47.268221
139	3	39.9	2026-01-18 18:49:47.271488
140	5	40	2026-01-18 18:49:47.274991
141	1	38.9	2026-01-18 18:49:51.797134
142	2	41.4	2026-01-18 18:49:51.802788
143	3	39.7	2026-01-18 18:49:51.807547
144	5	39.8	2026-01-18 18:49:51.81257
145	1	38.5	2026-01-18 18:49:56.260818
146	2	40.9	2026-01-18 18:49:56.265333
147	3	39.2	2026-01-18 18:49:56.26927
148	5	39.6	2026-01-18 18:49:56.27309
149	1	38.4	2026-01-18 18:50:01.219337
150	2	40.5	2026-01-18 18:50:01.244572
151	3	38.7	2026-01-18 18:50:01.274295
152	5	39.4	2026-01-18 18:50:01.292491
153	1	38.1	2026-01-18 18:50:05.281154
154	2	40.1	2026-01-18 18:50:05.285405
155	3	38.5	2026-01-18 18:50:05.29447
156	5	39	2026-01-18 18:50:05.298033
157	1	37.8	2026-01-18 18:50:13.528713
158	2	39.6	2026-01-18 18:50:13.58021
159	3	38.2	2026-01-18 18:50:13.629866
160	5	38.5	2026-01-18 18:50:13.644006
161	1	37.6	2026-01-18 18:50:17.509069
162	2	39.5	2026-01-18 18:50:17.513877
163	3	37.9	2026-01-18 18:50:17.518406
164	5	38	2026-01-18 18:50:17.522473
165	1	37.1	2026-01-18 18:50:22.203564
166	2	39.2	2026-01-18 18:50:22.208884
167	3	37.5	2026-01-18 18:50:22.214427
168	5	37.8	2026-01-18 18:50:22.219186
169	1	37	2026-01-18 18:50:27.260419
170	2	38.9	2026-01-18 18:50:27.265318
171	3	37	2026-01-18 18:50:27.269566
172	5	37.5	2026-01-18 18:50:27.273424
173	1	36.9	2026-01-18 18:50:31.76292
174	2	38.5	2026-01-18 18:50:31.768014
175	3	36.5	2026-01-18 18:50:31.772748
176	5	37.4	2026-01-18 18:50:31.777501
177	1	36.7	2026-01-18 18:50:36.791433
178	2	38	2026-01-18 18:50:36.807999
179	3	36	2026-01-18 18:50:36.825276
180	5	37.3	2026-01-18 18:50:36.832763
181	1	36.2	2026-01-18 18:50:41.113312
182	2	37.6	2026-01-18 18:50:41.129944
183	3	35.8	2026-01-18 18:50:41.167163
184	5	37.1	2026-01-18 18:50:41.196744
185	1	35.9	2026-01-18 18:50:45.31086
186	2	37.4	2026-01-18 18:50:45.315134
187	3	35.3	2026-01-18 18:50:45.318913
188	5	36.9	2026-01-18 18:50:45.322946
189	1	35.4	2026-01-18 18:50:49.804437
190	2	37	2026-01-18 18:50:49.808458
191	3	35.1	2026-01-18 18:50:49.812052
192	5	36.7	2026-01-18 18:50:49.815477
193	1	35.3	2026-01-18 18:50:54.31338
194	2	36.7	2026-01-18 18:50:54.31807
195	3	35	2026-01-18 18:50:54.3217
196	5	36.2	2026-01-18 18:50:54.325742
197	1	34.9	2026-01-18 18:51:03.767787
198	2	36.4	2026-01-18 18:51:03.779195
199	3	34.6	2026-01-18 18:51:03.785877
200	5	35.7	2026-01-18 18:51:03.794367
201	1	34.8	2026-01-18 18:51:07.672955
202	2	36	2026-01-18 18:51:07.678314
203	3	34.1	2026-01-18 18:51:07.683056
204	5	35.4	2026-01-18 18:51:07.69031
205	1	34.7	2026-01-18 18:51:12.105677
206	2	35.8	2026-01-18 18:51:12.111023
207	3	33.7	2026-01-18 18:51:12.115316
208	5	35.3	2026-01-18 18:51:12.119565
209	1	34.5	2026-01-18 18:51:16.615911
210	2	35.5	2026-01-18 18:51:16.620811
211	3	33.3	2026-01-18 18:51:16.626537
212	5	34.8	2026-01-18 18:51:16.631436
213	1	34.4	2026-01-18 18:51:23.557228
214	2	35.2	2026-01-18 18:51:23.562249
215	3	33.1	2026-01-18 18:51:23.567187
216	5	34.4	2026-01-18 18:51:23.572203
217	1	33.9	2026-01-18 18:51:28.556639
218	2	34.7	2026-01-18 18:51:28.579071
219	3	32.9	2026-01-18 18:51:28.584042
220	5	34.3	2026-01-18 18:51:28.590155
221	1	33.4	2026-01-18 18:51:32.606875
222	2	34.6	2026-01-18 18:51:32.6141
223	3	32.6	2026-01-18 18:51:32.61891
224	5	34.2	2026-01-18 18:51:32.623648
225	1	33.3	2026-01-18 18:51:38.211101
226	2	34.2	2026-01-18 18:51:38.216172
227	3	32.2	2026-01-18 18:51:38.22222
228	5	34.1	2026-01-18 18:51:38.22855
229	1	33	2026-01-18 18:51:41.900457
230	2	33.7	2026-01-18 18:51:41.910314
231	3	31.8	2026-01-18 18:51:41.919885
232	5	33.6	2026-01-18 18:51:41.933162
233	1	32.6	2026-01-18 18:51:46.054736
234	2	33.4	2026-01-18 18:51:46.059614
235	3	31.5	2026-01-18 18:51:46.064094
236	5	33.5	2026-01-18 18:51:46.068712
237	1	32.5	2026-01-18 18:51:53.926287
238	2	33	2026-01-18 18:51:53.930529
239	3	31	2026-01-18 18:51:53.934244
240	5	33.1	2026-01-18 18:51:53.938078
241	1	32.3	2026-01-18 18:51:57.64801
242	2	32.5	2026-01-18 18:51:57.653914
243	3	30.8	2026-01-18 18:51:57.658195
244	5	32.8	2026-01-18 18:51:57.662059
245	1	32	2026-01-18 18:52:02.793065
246	2	32.3	2026-01-18 18:52:02.814018
247	3	30.3	2026-01-18 18:52:02.825085
248	5	32.3	2026-01-18 18:52:02.83255
249	1	31.9	2026-01-18 18:52:07.396871
250	2	31.8	2026-01-18 18:52:07.401918
251	3	30	2026-01-18 18:52:07.406561
252	5	32.1	2026-01-18 18:52:07.41432
253	1	31.6	2026-01-18 18:52:11.188009
254	2	31.5	2026-01-18 18:52:11.195307
255	3	29.9	2026-01-18 18:52:11.200585
256	5	31.8	2026-01-18 18:52:11.205012
257	1	31.3	2026-01-18 18:52:50.138045
258	2	31.4	2026-01-18 18:52:50.143501
259	3	29.5	2026-01-18 18:52:50.148702
260	5	31.6	2026-01-18 18:52:50.163455
261	1	30.9	2026-01-18 18:52:54.637831
262	2	30.9	2026-01-18 18:52:54.642709
263	3	29.1	2026-01-18 18:52:54.647067
264	5	31.5	2026-01-18 18:52:54.656326
265	1	30.8	2026-01-18 18:53:02.783788
266	2	30.6	2026-01-18 18:53:02.813607
267	3	28.9	2026-01-18 18:53:02.848962
268	5	31.2	2026-01-18 18:53:02.881133
269	1	30.4	2026-01-18 18:53:07.067615
270	2	30.1	2026-01-18 18:53:07.076974
271	3	28.8	2026-01-18 18:53:07.085066
272	5	30.8	2026-01-18 18:53:07.098831
273	1	30.1	2026-01-18 18:53:11.498408
274	2	29.6	2026-01-18 18:53:11.503493
275	3	28.6	2026-01-18 18:53:11.513097
276	5	30.7	2026-01-18 18:53:11.522178
277	1	29.9	2026-01-18 18:53:15.995803
278	2	29.1	2026-01-18 18:53:16.008647
279	3	28.5	2026-01-18 18:53:16.017213
280	5	30.5	2026-01-18 18:53:16.029008
281	1	29.4	2026-01-18 18:53:20.463443
282	2	28.9	2026-01-18 18:53:20.474199
283	3	28	2026-01-18 18:53:20.483133
284	5	30.3	2026-01-18 18:53:20.491409
285	1	29.3	2026-01-18 18:53:25.637595
286	2	28.6	2026-01-18 18:53:25.657083
287	3	27.6	2026-01-18 18:53:25.665658
288	5	29.8	2026-01-18 18:53:25.675917
289	1	29	2026-01-18 18:53:29.512075
290	2	28.3	2026-01-18 18:53:29.522575
291	3	27.2	2026-01-18 18:53:29.536591
292	5	29.5	2026-01-18 18:53:29.547257
293	1	28.6	2026-01-18 18:53:37.332758
294	2	28	2026-01-18 18:53:37.34364
295	3	27	2026-01-18 18:53:37.352797
296	5	29.4	2026-01-18 18:53:37.362233
297	1	28.2	2026-01-18 18:53:41.83412
298	2	27.9	2026-01-18 18:53:41.844913
299	3	26.7	2026-01-18 18:53:41.8565
300	5	29.1	2026-01-18 18:53:41.86467
301	1	27.8	2026-01-18 18:53:46.32896
302	2	27.8	2026-01-18 18:53:46.341057
303	3	26.2	2026-01-18 18:53:46.350775
304	5	28.9	2026-01-18 18:53:46.360371
305	1	27.5	2026-01-18 18:53:51.308023
306	2	27.5	2026-01-18 18:53:51.389413
307	3	25.7	2026-01-18 18:53:51.471427
308	5	28.7	2026-01-18 18:53:51.48725
309	1	27.1	2026-01-18 18:53:55.941006
310	2	27.1	2026-01-18 18:53:55.952083
311	3	25.2	2026-01-18 18:53:55.960837
312	5	28.2	2026-01-18 18:53:55.971608
313	1	26.8	2026-01-18 18:53:59.838198
314	2	26.6	2026-01-18 18:53:59.848261
315	3	25.1	2026-01-18 18:53:59.856891
316	5	28	2026-01-18 18:53:59.869716
317	1	26.4	2026-01-18 18:54:07.600767
318	2	26.3	2026-01-18 18:54:07.612225
319	3	24.6	2026-01-18 18:54:07.621578
320	5	27.9	2026-01-18 18:54:07.629994
321	1	26.3	2026-01-18 19:35:40.464237
322	2	25.9	2026-01-18 19:35:40.502606
323	3	24.3	2026-01-18 19:35:40.510454
324	5	27.5	2026-01-18 19:35:40.518959
325	1	25.8	2026-01-18 19:35:45.442154
326	2	25.4	2026-01-18 19:35:45.449594
327	3	24	2026-01-18 19:35:45.456466
328	5	27.2	2026-01-18 19:35:45.465258
329	1	25.5	2026-01-18 19:35:53.969223
330	2	24.9	2026-01-18 19:35:54.00358
331	3	23.6	2026-01-18 19:35:54.017946
332	5	27	2026-01-18 19:35:54.029732
333	1	25.1	2026-01-18 19:36:08.867268
334	2	24.7	2026-01-18 19:36:08.876719
335	3	23.3	2026-01-18 19:36:08.882867
336	5	26.8	2026-01-18 19:36:08.88859
337	1	24.9	2026-01-18 19:36:15.487282
338	2	24.4	2026-01-18 19:36:15.498575
339	3	22.9	2026-01-18 19:36:15.506496
340	5	26.7	2026-01-18 19:36:15.51613
341	1	24.4	2026-01-18 19:36:20.4651
342	2	24.3	2026-01-18 19:36:20.475907
343	3	22.8	2026-01-18 19:36:20.484086
344	5	26.4	2026-01-18 19:36:20.491095
345	1	24.3	2026-01-18 19:36:25.410282
346	2	24	2026-01-18 19:36:25.419348
347	3	22.4	2026-01-18 19:36:25.42748
348	5	25.9	2026-01-18 19:36:25.435784
\.


--
-- Data for Name: sensors; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.sensors (id, region_id, name, type, is_active, created_at, updated_at) FROM stdin;
1	1	Czujnik wilgotności gleby 1	SOIL_MOISTURE	t	2026-01-17 00:00:01.772732	\N
2	1	Czujnik temperatury powietrza	AIR_TEMPERATURE	t	2026-01-17 00:00:01.772732	\N
3	2	Czujnik nasłonecznienia	LIGHT	t	2026-01-17 00:00:01.772732	\N
5	3	Cytron Maker Soil Moisture1	Czujnik wilgotności gleby	t	2026-01-17 21:22:45.56475	2026-01-17 21:22:56.138987
\.


--
-- Data for Name: user_preferences; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.user_preferences (id, user_id, email_notifications_enabled, created_at, updated_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.users (id, email, password_hash, nickname, full_name, role, is_active, created_at, updated_at) FROM stdin;
1	owner@example.com	$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx	owner.user	Owner User	OWNER	t	2026-01-17 00:00:01.772732	\N
2	worker@example.com	$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx	worker.user	Worker User	WORKER	t	2026-01-17 00:00:01.772732	\N
3	admin@example.com	$2y$10$abcdefghijklmnopqrstuvABCDEFGHijklmnOPQRSTUVwx	admin.user	Admin User	ADMIN	t	2026-01-17 00:00:01.772732	\N
4	michalbrzoza@gmail.com	$2y$10$FC9R5BuIVtVGs9V54GGwJeRcoCMqeSsgpzY2RxoK6eYYbRM.eAcDW	michał.brzeziński.official	Michał Brzeziński	OWNER	t	2026-01-17 00:00:15.559911	2026-01-17 22:29:57.891371
\.


--
-- Data for Name: watering_actions; Type: TABLE DATA; Schema: public; Owner: docker
--

COPY public.watering_actions (id, region_id, schedule_id, initiated_by, started_at, stopped_at, status, volume_liters) FROM stdin;
1	3	\N	4	2026-01-17 22:11:01.490463	2026-01-17 22:11:03.511236	COMPLETED	7.00
2	3	\N	4	2026-01-17 22:11:14.840291	2026-01-17 22:11:16.845825	COMPLETED	8.00
3	3	\N	4	2026-01-18 19:36:01.519482	2026-01-18 19:36:03.549448	COMPLETED	9.00
\.


--
-- Name: cameras_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.cameras_id_seq', 3, true);


--
-- Name: login_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.login_attempts_id_seq', 1, false);


--
-- Name: notifications_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.notifications_id_seq', 76, true);


--
-- Name: regions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.regions_id_seq', 7, true);


--
-- Name: schedules_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.schedules_id_seq', 3, true);


--
-- Name: sensor_readings_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.sensor_readings_id_seq', 348, true);


--
-- Name: sensors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.sensors_id_seq', 6, true);


--
-- Name: user_preferences_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.user_preferences_id_seq', 1, false);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.users_id_seq', 6, true);


--
-- Name: watering_actions_id_seq; Type: SEQUENCE SET; Schema: public; Owner: docker
--

SELECT pg_catalog.setval('public.watering_actions_id_seq', 3, true);


--
-- Name: cameras cameras_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cameras
    ADD CONSTRAINT cameras_pkey PRIMARY KEY (id);


--
-- Name: login_attempts login_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.login_attempts
    ADD CONSTRAINT login_attempts_pkey PRIMARY KEY (id);


--
-- Name: notifications notifications_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.notifications
    ADD CONSTRAINT notifications_pkey PRIMARY KEY (id);


--
-- Name: regions regions_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.regions
    ADD CONSTRAINT regions_pkey PRIMARY KEY (id);


--
-- Name: schedules schedules_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_pkey PRIMARY KEY (id);


--
-- Name: sensor_readings sensor_readings_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.sensor_readings
    ADD CONSTRAINT sensor_readings_pkey PRIMARY KEY (id);


--
-- Name: sensors sensors_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.sensors
    ADD CONSTRAINT sensors_pkey PRIMARY KEY (id);


--
-- Name: user_preferences user_preferences_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_preferences
    ADD CONSTRAINT user_preferences_pkey PRIMARY KEY (id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_nickname_key; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_nickname_key UNIQUE (nickname);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: watering_actions watering_actions_pkey; Type: CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.watering_actions
    ADD CONSTRAINT watering_actions_pkey PRIMARY KEY (id);


--
-- Name: cameras cameras_region_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.cameras
    ADD CONSTRAINT cameras_region_id_fkey FOREIGN KEY (region_id) REFERENCES public.regions(id) ON DELETE CASCADE;


--
-- Name: regions regions_owner_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.regions
    ADD CONSTRAINT regions_owner_id_fkey FOREIGN KEY (owner_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: schedules schedules_region_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.schedules
    ADD CONSTRAINT schedules_region_id_fkey FOREIGN KEY (region_id) REFERENCES public.regions(id) ON DELETE CASCADE;


--
-- Name: sensors sensors_region_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.sensors
    ADD CONSTRAINT sensors_region_id_fkey FOREIGN KEY (region_id) REFERENCES public.regions(id) ON DELETE CASCADE;


--
-- Name: user_preferences user_preferences_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.user_preferences
    ADD CONSTRAINT user_preferences_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;


--
-- Name: watering_actions watering_actions_initiated_by_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.watering_actions
    ADD CONSTRAINT watering_actions_initiated_by_fkey FOREIGN KEY (initiated_by) REFERENCES public.users(id) ON DELETE SET NULL;


--
-- Name: watering_actions watering_actions_region_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.watering_actions
    ADD CONSTRAINT watering_actions_region_id_fkey FOREIGN KEY (region_id) REFERENCES public.regions(id) ON DELETE CASCADE;


--
-- Name: watering_actions watering_actions_schedule_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: docker
--

ALTER TABLE ONLY public.watering_actions
    ADD CONSTRAINT watering_actions_schedule_id_fkey FOREIGN KEY (schedule_id) REFERENCES public.schedules(id) ON DELETE SET NULL;


--
-- PostgreSQL database dump complete
--

\unrestrict Vf2omLDi31uC1Qjz20Wpxcm1l0T0CvloibeNM9RbvSc7czKBRXWibWCRe31wvCn

