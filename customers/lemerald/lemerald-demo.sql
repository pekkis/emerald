--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = public, pg_catalog;

--
-- Name: emerald_activity_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_activity_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_activity_id_seq OWNER TO pekkis;

--
-- Name: emerald_activity_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_activity_id_seq', 3, true);


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: emerald_activity; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_activity (
    id integer DEFAULT nextval('emerald_activity_id_seq'::regclass) NOT NULL,
    category character varying(255) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.emerald_activity OWNER TO pekkis;

--
-- Name: emerald_application_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_application_option (
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.emerald_application_option OWNER TO pekkis;

--
-- Name: emerald_filelib_file_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_filelib_file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_filelib_file_id_seq OWNER TO pekkis;

--
-- Name: emerald_filelib_file_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_filelib_file_id_seq', 1, false);


--
-- Name: emerald_filelib_file; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_filelib_file (
    id integer DEFAULT nextval('emerald_filelib_file_id_seq'::regclass) NOT NULL,
    folder_id integer NOT NULL,
    mimetype character varying(255) NOT NULL,
    profile character varying(255) DEFAULT 'default'::character varying NOT NULL,
    size integer,
    name character varying(255) NOT NULL,
    link character varying(1000) DEFAULT NULL::character varying
);


ALTER TABLE public.emerald_filelib_file OWNER TO pekkis;

--
-- Name: emerald_filelib_folder_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_filelib_folder_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_filelib_folder_id_seq OWNER TO pekkis;

--
-- Name: emerald_filelib_folder_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_filelib_folder_id_seq', 1, true);


--
-- Name: emerald_filelib_folder; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_filelib_folder (
    id integer DEFAULT nextval('emerald_filelib_folder_id_seq'::regclass) NOT NULL,
    parent_id integer,
    name character varying(255) NOT NULL
);


ALTER TABLE public.emerald_filelib_folder OWNER TO pekkis;

--
-- Name: emerald_form_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_form_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_form_id_seq OWNER TO pekkis;

--
-- Name: emerald_form_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_form_id_seq', 1, false);


--
-- Name: emerald_form; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_form (
    id integer DEFAULT nextval('emerald_form_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.emerald_form OWNER TO pekkis;

--
-- Name: emerald_form_field_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_form_field_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_form_field_id_seq OWNER TO pekkis;

--
-- Name: emerald_form_field_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_form_field_id_seq', 1, false);


--
-- Name: emerald_form_field; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_form_field (
    id integer DEFAULT nextval('emerald_form_field_id_seq'::regclass) NOT NULL,
    form_id integer NOT NULL,
    type smallint NOT NULL,
    order_id smallint DEFAULT 0::smallint NOT NULL,
    title character varying(255) DEFAULT NULL::character varying,
    mandatory smallint DEFAULT 0::smallint NOT NULL,
    options text
);


ALTER TABLE public.emerald_form_field OWNER TO pekkis;

--
-- Name: emerald_htmlcontent; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_htmlcontent (
    page_id integer NOT NULL,
    block_id integer NOT NULL,
    content text
);


ALTER TABLE public.emerald_htmlcontent OWNER TO pekkis;

--
-- Name: emerald_locale; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_locale (
    locale character varying(6) NOT NULL,
    page_start integer
);


ALTER TABLE public.emerald_locale OWNER TO pekkis;

--
-- Name: emerald_locale_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_locale_option (
    locale_locale character varying(6) NOT NULL,
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.emerald_locale_option OWNER TO pekkis;

--
-- Name: emerald_news_channel_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_news_channel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_news_channel_id_seq OWNER TO pekkis;

--
-- Name: emerald_news_channel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_news_channel_id_seq', 3, true);


--
-- Name: emerald_news_channel; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_news_channel (
    id integer DEFAULT nextval('emerald_news_channel_id_seq'::regclass) NOT NULL,
    page_id integer NOT NULL,
    items_per_page smallint DEFAULT 10::smallint NOT NULL,
    link_readmore character varying(255) NOT NULL,
    allow_syndication smallint DEFAULT 1::smallint NOT NULL,
    default_months_valid smallint DEFAULT 12::smallint,
    title character varying(255) NOT NULL,
    description text,
    locale character varying(6) DEFAULT NULL::character varying,
    copyright character varying(255) DEFAULT NULL::character varying,
    managing_editor character varying(255) DEFAULT NULL::character varying,
    webmaster character varying(255) DEFAULT NULL::character varying,
    category character varying(255) DEFAULT NULL::character varying,
    ttl smallint DEFAULT 60::smallint NOT NULL,
    skip_hours character varying(255) DEFAULT NULL::character varying,
    skip_days character varying(255) DEFAULT NULL::character varying,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.emerald_news_channel OWNER TO pekkis;

--
-- Name: emerald_news_item_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_news_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_news_item_id_seq OWNER TO pekkis;

--
-- Name: emerald_news_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_news_item_id_seq', 3, true);


--
-- Name: emerald_news_item; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_news_item (
    id integer DEFAULT nextval('emerald_news_item_id_seq'::regclass) NOT NULL,
    news_channel_id integer NOT NULL,
    title character varying(255) NOT NULL,
    description text,
    article text,
    author character varying(255) DEFAULT NULL::character varying,
    category character varying(255) DEFAULT NULL::character varying,
    comments character varying(255) DEFAULT NULL::character varying,
    enclosure character varying(255) DEFAULT NULL::character varying,
    valid_start timestamp without time zone NOT NULL,
    valid_end timestamp without time zone,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.emerald_news_item OWNER TO pekkis;

--
-- Name: emerald_page_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_page_id_seq OWNER TO pekkis;

--
-- Name: emerald_page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_page_id_seq', 11, true);


--
-- Name: emerald_page; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_page (
    id integer DEFAULT nextval('emerald_page_id_seq'::regclass) NOT NULL,
    global_id integer NOT NULL,
    locale character varying(6) NOT NULL,
    parent_id integer,
    order_id smallint DEFAULT 0::smallint NOT NULL,
    layout character varying(255) DEFAULT 'Default'::character varying,
    title character varying(255) NOT NULL,
    beautifurl character varying(1000) DEFAULT NULL::character varying,
    path character varying(255),
    shard_id integer NOT NULL,
    visibility smallint DEFAULT 1::smallint NOT NULL,
    cache_seconds integer DEFAULT 0 NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL,
    redirect_id integer
);


ALTER TABLE public.emerald_page OWNER TO pekkis;

--
-- Name: emerald_page_global_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_page_global_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_page_global_id_seq OWNER TO pekkis;

--
-- Name: emerald_page_global_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_page_global_id_seq', 10, true);


--
-- Name: emerald_page_global; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_page_global (
    id integer DEFAULT nextval('emerald_page_global_id_seq'::regclass) NOT NULL
);


ALTER TABLE public.emerald_page_global OWNER TO pekkis;

--
-- Name: emerald_permission_activity_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_permission_activity_ugroup (
    activity_id integer NOT NULL,
    ugroup_id integer NOT NULL
);


ALTER TABLE public.emerald_permission_activity_ugroup OWNER TO pekkis;

--
-- Name: emerald_permission_folder_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_permission_folder_ugroup (
    folder_id integer NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.emerald_permission_folder_ugroup OWNER TO pekkis;

--
-- Name: emerald_permission_locale_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_permission_locale_ugroup (
    locale_locale character varying(6) NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.emerald_permission_locale_ugroup OWNER TO pekkis;

--
-- Name: emerald_permission_page_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_permission_page_ugroup (
    page_id integer NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.emerald_permission_page_ugroup OWNER TO pekkis;

--
-- Name: emerald_shard; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_shard (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    module character varying(255) DEFAULT 'core'::character varying NOT NULL,
    controller character varying(255) DEFAULT 'index'::character varying NOT NULL,
    action character varying(255) DEFAULT 'index'::character varying NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.emerald_shard OWNER TO pekkis;

--
-- Name: emerald_ugroup_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_ugroup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_ugroup_id_seq OWNER TO pekkis;

--
-- Name: emerald_ugroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_ugroup_id_seq', 2, true);


--
-- Name: emerald_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_ugroup (
    id integer DEFAULT nextval('emerald_ugroup_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.emerald_ugroup OWNER TO pekkis;

--
-- Name: emerald_user_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE emerald_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.emerald_user_id_seq OWNER TO pekkis;

--
-- Name: emerald_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('emerald_user_id_seq', 1, true);


--
-- Name: emerald_user; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_user (
    id integer DEFAULT nextval('emerald_user_id_seq'::regclass) NOT NULL,
    email character varying(255) NOT NULL,
    passwd character(32) NOT NULL,
    firstname character varying(255) DEFAULT NULL::character varying,
    lastname character varying(255) DEFAULT NULL::character varying,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.emerald_user OWNER TO pekkis;

--
-- Name: emerald_user_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_user_option (
    user_id integer NOT NULL,
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.emerald_user_option OWNER TO pekkis;

--
-- Name: emerald_user_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_user_ugroup (
    user_id integer NOT NULL,
    ugroup_id integer NOT NULL
);


ALTER TABLE public.emerald_user_ugroup OWNER TO pekkis;

--
-- Data for Name: emerald_activity; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_activity (id, category, name) FROM stdin;
1	Administration	Edit activity permissions
2	Administration	Clear caches
3	Administration	Expose admin panel
\.


--
-- Data for Name: emerald_application_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_application_option (identifier, strvalue) FROM stdin;
installed	1
default_locale	fi_FI
\.


--
-- Data for Name: emerald_filelib_file; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_filelib_file (id, folder_id, mimetype, profile, size, name, link) FROM stdin;
\.


--
-- Data for Name: emerald_filelib_folder; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_filelib_folder (id, parent_id, name) FROM stdin;
1	\N	root
\.


--
-- Data for Name: emerald_form; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_form (id, name, description, status) FROM stdin;
\.


--
-- Data for Name: emerald_form_field; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_form_field (id, form_id, type, order_id, title, mandatory, options) FROM stdin;
\.


--
-- Data for Name: emerald_htmlcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_htmlcontent (page_id, block_id, content) FROM stdin;
2	1	<h1>Tervetuloa Emeraldiin!</h1>\n<p>Huhhahhei ja rommia pullo!</p>
1	1	<h1>Welcome to Emerald</h1>\n<p>Oh sweet mama!</p>
6	1	<h1>Willkommen nach Emerald</h1>\n<p>Zum teufel! Jawohl!</p>
\.


--
-- Data for Name: emerald_locale; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_locale (locale, page_start) FROM stdin;
en_US	\N
de_DE	\N
fi_FI	2
\.


--
-- Data for Name: emerald_locale_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_locale_option (locale_locale, identifier, strvalue) FROM stdin;
\.


--
-- Data for Name: emerald_news_channel; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_news_channel (id, page_id, items_per_page, link_readmore, allow_syndication, default_months_valid, title, description, locale, copyright, managing_editor, webmaster, category, ttl, skip_hours, skip_days, status) FROM stdin;
1	9	10	Lue lisää	1	12	Uutiset	\N	\N	\N	\N	\N	\N	60	\N	\N	0
2	11	10	Read more	1	12	News	\N	\N	\N	\N	\N	\N	60	\N	\N	0
3	10	10	Mehr lesen!	1	12	News		\N	\N	\N	\N	\N	60	\N	\N	0
\.


--
-- Data for Name: emerald_news_item; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_news_item (id, news_channel_id, title, description, article, author, category, comments, enclosure, valid_start, valid_end, status) FROM stdin;
1	1	Uutinen	Uutisen ingressi	<p>Uutisen sis&auml;lt&ouml;</p>	\N	\N	\N	\N	2010-03-25 00:00:00	2011-03-25 00:00:00	1
2	3	Einer nachricht	Nachricht titel	<p>Ein zwei drei</p>	\N	\N	\N	\N	2010-03-25 00:00:00	2011-03-25 00:00:00	1
3	2	Shocking news!	Shock to the system!	<p>Shocker!</p>	\N	\N	\N	\N	2010-03-25 00:00:00	2011-03-25 00:00:00	1
\.


--
-- Data for Name: emerald_page; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_page (id, global_id, locale, parent_id, order_id, layout, title, beautifurl, path, shard_id, visibility, cache_seconds, status, redirect_id) FROM stdin;
2	1	fi_FI	\N	1	Default	Tervetuloa	fi_FI/tervetuloa	[2]	1	1	0	0	\N
9	9	fi_FI	\N	1	Default	Uutiset	fi_FI/uutiset	[9]	3	1	0	0	\N
1	1	en_US	\N	1	Default	Welcome	en_US/welcome	[1]	1	1	0	0	\N
11	9	en_US	\N	2	Default	News	en_US/news	[11]	3	1	0	0	\N
6	1	de_DE	\N	1	Default	Willkommen	de_DE/willkommen	[6]	1	1	0	0	\N
10	9	de_DE	\N	2	Default	Nachrichten	de_DE/nachrichten	[10]	3	1	0	0	\N
\.


--
-- Data for Name: emerald_page_global; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_page_global (id) FROM stdin;
1
2
3
4
5
6
7
8
9
10
\.


--
-- Data for Name: emerald_permission_activity_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_activity_ugroup (activity_id, ugroup_id) FROM stdin;
\.


--
-- Data for Name: emerald_permission_folder_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_folder_ugroup (folder_id, ugroup_id, permission) FROM stdin;
\.


--
-- Data for Name: emerald_permission_locale_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_locale_ugroup (locale_locale, ugroup_id, permission) FROM stdin;
fi_FI	1	4
fi_FI	2	7
en_US	1	4
en_US	2	7
de_DE	1	4
de_DE	2	7
\.


--
-- Data for Name: emerald_permission_page_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_page_ugroup (page_id, ugroup_id, permission) FROM stdin;
1	1	4
1	2	7
6	1	4
6	2	7
2	1	4
2	2	7
9	1	4
9	2	7
10	1	4
10	2	7
11	1	4
11	2	7
\.


--
-- Data for Name: emerald_shard; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_shard (id, name, module, controller, action, status) FROM stdin;
1	Html	core	html-content	index	3
2	Form	core	form-content	index	3
3	News	core	news	index	3
\.


--
-- Data for Name: emerald_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_ugroup (id, name) FROM stdin;
1	Anonymous
2	Root
\.


--
-- Data for Name: emerald_user; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_user (id, email, passwd, firstname, lastname, status) FROM stdin;
1	puhemies@diktaattoriporssi.com	4ee6d203733c39cb3910c3371d56e1f3	\N	\N	1
\.


--
-- Data for Name: emerald_user_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_user_option (user_id, identifier, strvalue) FROM stdin;
\.


--
-- Data for Name: emerald_user_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_user_ugroup (user_id, ugroup_id) FROM stdin;
1	2
\.


--
-- Name: emerald_activity_category_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_activity
    ADD CONSTRAINT emerald_activity_category_key UNIQUE (category, name);


--
-- Name: emerald_activity_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_activity
    ADD CONSTRAINT emerald_activity_pkey PRIMARY KEY (id);


--
-- Name: emerald_application_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_application_option
    ADD CONSTRAINT emerald_application_option_pkey PRIMARY KEY (identifier);


--
-- Name: emerald_filelib_file_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_filelib_file
    ADD CONSTRAINT emerald_filelib_file_name_key UNIQUE (name, folder_id);


--
-- Name: emerald_filelib_file_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_filelib_file
    ADD CONSTRAINT emerald_filelib_file_pkey PRIMARY KEY (id);


--
-- Name: emerald_filelib_folder_parent_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_filelib_folder
    ADD CONSTRAINT emerald_filelib_folder_parent_id_key UNIQUE (parent_id, name);


--
-- Name: emerald_filelib_folder_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_filelib_folder
    ADD CONSTRAINT emerald_filelib_folder_pkey PRIMARY KEY (id);


--
-- Name: emerald_form_field_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_form_field
    ADD CONSTRAINT emerald_form_field_pkey PRIMARY KEY (id);


--
-- Name: emerald_form_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_form
    ADD CONSTRAINT emerald_form_name_key UNIQUE (name);


--
-- Name: emerald_form_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_form
    ADD CONSTRAINT emerald_form_pkey PRIMARY KEY (id);


--
-- Name: emerald_htmlcontent_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_htmlcontent
    ADD CONSTRAINT emerald_htmlcontent_pkey PRIMARY KEY (page_id, block_id);


--
-- Name: emerald_locale_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_locale_option
    ADD CONSTRAINT emerald_locale_option_pkey PRIMARY KEY (locale_locale, identifier);


--
-- Name: emerald_locale_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_locale
    ADD CONSTRAINT emerald_locale_pkey PRIMARY KEY (locale);


--
-- Name: emerald_news_channel_page_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_news_channel
    ADD CONSTRAINT emerald_news_channel_page_id_key UNIQUE (page_id);


--
-- Name: emerald_news_channel_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_news_channel
    ADD CONSTRAINT emerald_news_channel_pkey PRIMARY KEY (id);


--
-- Name: emerald_news_item_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_news_item
    ADD CONSTRAINT emerald_news_item_pkey PRIMARY KEY (id);


--
-- Name: emerald_page_global_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_global_id_key UNIQUE (global_id, locale);


--
-- Name: emerald_page_global_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_page_global
    ADD CONSTRAINT emerald_page_global_pkey PRIMARY KEY (id);


--
-- Name: emerald_page_parent_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_parent_id_key UNIQUE (parent_id, title);


--
-- Name: emerald_page_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_pkey PRIMARY KEY (id);


--
-- Name: emerald_permission_activity_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_permission_activity_ugroup
    ADD CONSTRAINT emerald_permission_activity_ugroup_pkey PRIMARY KEY (activity_id, ugroup_id);


--
-- Name: emerald_permission_folder_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_permission_folder_ugroup
    ADD CONSTRAINT emerald_permission_folder_ugroup_pkey PRIMARY KEY (folder_id, ugroup_id);


--
-- Name: emerald_permission_locale_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_permission_locale_ugroup
    ADD CONSTRAINT emerald_permission_locale_ugroup_pkey PRIMARY KEY (locale_locale, ugroup_id);


--
-- Name: emerald_permission_page_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_permission_page_ugroup
    ADD CONSTRAINT emerald_permission_page_ugroup_pkey PRIMARY KEY (page_id, ugroup_id);


--
-- Name: emerald_shard_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_shard
    ADD CONSTRAINT emerald_shard_name_key UNIQUE (name);


--
-- Name: emerald_shard_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_shard
    ADD CONSTRAINT emerald_shard_pkey PRIMARY KEY (id);


--
-- Name: emerald_ugroup_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_ugroup
    ADD CONSTRAINT emerald_ugroup_name_key UNIQUE (name);


--
-- Name: emerald_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_ugroup
    ADD CONSTRAINT emerald_ugroup_pkey PRIMARY KEY (id);


--
-- Name: emerald_user_email_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_user
    ADD CONSTRAINT emerald_user_email_key UNIQUE (email);


--
-- Name: emerald_user_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_user_option
    ADD CONSTRAINT emerald_user_option_pkey PRIMARY KEY (user_id, identifier);


--
-- Name: emerald_user_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_user
    ADD CONSTRAINT emerald_user_pkey PRIMARY KEY (id);


--
-- Name: emerald_user_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_user_ugroup
    ADD CONSTRAINT emerald_user_ugroup_pkey PRIMARY KEY (user_id, ugroup_id);


--
-- Name: page_beautifurl_idx; Type: INDEX; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE UNIQUE INDEX page_beautifurl_idx ON emerald_page USING btree (beautifurl);


--
-- Name: emerald_filelib_file_folder_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_filelib_file
    ADD CONSTRAINT emerald_filelib_file_folder_id_fkey FOREIGN KEY (folder_id) REFERENCES emerald_filelib_folder(id) ON UPDATE CASCADE;


--
-- Name: emerald_filelib_folder_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_filelib_folder
    ADD CONSTRAINT emerald_filelib_folder_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES emerald_filelib_folder(id) ON UPDATE CASCADE;


--
-- Name: emerald_form_field_form_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_form_field
    ADD CONSTRAINT emerald_form_field_form_id_fkey FOREIGN KEY (form_id) REFERENCES emerald_form(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_htmlcontent_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_htmlcontent
    ADD CONSTRAINT emerald_htmlcontent_page_id_fkey FOREIGN KEY (page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_locale_option_locale_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_locale_option
    ADD CONSTRAINT emerald_locale_option_locale_locale_fkey FOREIGN KEY (locale_locale) REFERENCES emerald_locale(locale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_locale_page_start_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_locale
    ADD CONSTRAINT emerald_locale_page_start_fkey FOREIGN KEY (page_start) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: emerald_news_channel_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_news_channel
    ADD CONSTRAINT emerald_news_channel_page_id_fkey FOREIGN KEY (page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_news_item_news_channel_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_news_item
    ADD CONSTRAINT emerald_news_item_news_channel_id_fkey FOREIGN KEY (news_channel_id) REFERENCES emerald_news_channel(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_page_global_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_global_id_fkey FOREIGN KEY (global_id) REFERENCES emerald_page_global(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_page_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_locale_fkey FOREIGN KEY (locale) REFERENCES emerald_locale(locale) ON UPDATE CASCADE;


--
-- Name: emerald_page_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_page_shard_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_shard_id_fkey FOREIGN KEY (shard_id) REFERENCES emerald_shard(id) ON UPDATE CASCADE;


--
-- Name: emerald_permission_activity_ugroup_activity_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_activity_ugroup
    ADD CONSTRAINT emerald_permission_activity_ugroup_activity_id_fkey FOREIGN KEY (activity_id) REFERENCES emerald_activity(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_activity_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_activity_ugroup
    ADD CONSTRAINT emerald_permission_activity_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_folder_ugroup_folder_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_folder_ugroup
    ADD CONSTRAINT emerald_permission_folder_ugroup_folder_id_fkey FOREIGN KEY (folder_id) REFERENCES emerald_filelib_folder(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_folder_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_folder_ugroup
    ADD CONSTRAINT emerald_permission_folder_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_locale_ugroup_locale_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_locale_ugroup
    ADD CONSTRAINT emerald_permission_locale_ugroup_locale_locale_fkey FOREIGN KEY (locale_locale) REFERENCES emerald_locale(locale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_locale_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_locale_ugroup
    ADD CONSTRAINT emerald_permission_locale_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_page_ugroup_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_page_ugroup
    ADD CONSTRAINT emerald_permission_page_ugroup_page_id_fkey FOREIGN KEY (page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_permission_page_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_permission_page_ugroup
    ADD CONSTRAINT emerald_permission_page_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_user_option_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_user_option
    ADD CONSTRAINT emerald_user_option_user_id_fkey FOREIGN KEY (user_id) REFERENCES emerald_user(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_user_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_user_ugroup
    ADD CONSTRAINT emerald_user_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES emerald_ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_user_ugroup_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_user_ugroup
    ADD CONSTRAINT emerald_user_ugroup_user_id_fkey FOREIGN KEY (user_id) REFERENCES emerald_user(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

