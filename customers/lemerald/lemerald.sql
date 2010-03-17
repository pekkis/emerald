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

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: application_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE application_option (
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.application_option OWNER TO pekkis;

--
-- Name: filelib_file_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE filelib_file_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.filelib_file_id_seq OWNER TO pekkis;

--
-- Name: filelib_file_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('filelib_file_id_seq', 2, true);


--
-- Name: filelib_file; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE filelib_file (
    id integer DEFAULT nextval('filelib_file_id_seq'::regclass) NOT NULL,
    folder_id integer NOT NULL,
    mimetype character varying(255) NOT NULL,
    profile character varying(255) DEFAULT 'default'::character varying NOT NULL,
    size integer,
    name character varying(255) NOT NULL,
    link character varying(1000) DEFAULT NULL::character varying
);


ALTER TABLE public.filelib_file OWNER TO pekkis;

--
-- Name: filelib_folder_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE filelib_folder_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.filelib_folder_id_seq OWNER TO pekkis;

--
-- Name: filelib_folder_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('filelib_folder_id_seq', 2, true);


--
-- Name: filelib_folder; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE filelib_folder (
    id integer DEFAULT nextval('filelib_folder_id_seq'::regclass) NOT NULL,
    parent_id integer,
    name character varying(255) NOT NULL
);


ALTER TABLE public.filelib_folder OWNER TO pekkis;

--
-- Name: form_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE form_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.form_id_seq OWNER TO pekkis;

--
-- Name: form_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('form_id_seq', 1, true);


--
-- Name: form; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE form (
    id integer DEFAULT nextval('form_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL,
    description text NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.form OWNER TO pekkis;

--
-- Name: form_field_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE form_field_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.form_field_id_seq OWNER TO pekkis;

--
-- Name: form_field_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('form_field_id_seq', 1, false);


--
-- Name: form_field; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE form_field (
    id integer DEFAULT nextval('form_field_id_seq'::regclass) NOT NULL,
    form_id integer NOT NULL,
    type smallint NOT NULL,
    order_id smallint DEFAULT 0::smallint NOT NULL,
    title character varying(255) DEFAULT NULL::character varying,
    mandatory smallint DEFAULT 0::smallint NOT NULL,
    options text
);


ALTER TABLE public.form_field OWNER TO pekkis;

--
-- Name: formcontent; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE formcontent (
    page_id integer NOT NULL,
    form_id integer,
    email_subject character varying(255) NOT NULL,
    email_from character varying(255) NOT NULL,
    email_to character varying(255) NOT NULL,
    redirect_page_id integer NOT NULL
);


ALTER TABLE public.formcontent OWNER TO pekkis;

--
-- Name: htmlcontent; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE htmlcontent (
    page_id integer NOT NULL,
    block_id integer NOT NULL,
    content text
);


ALTER TABLE public.htmlcontent OWNER TO pekkis;

--
-- Name: locale; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE locale (
    locale character varying(6) NOT NULL,
    page_start integer
);


ALTER TABLE public.locale OWNER TO pekkis;

--
-- Name: locale_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE locale_option (
    locale_locale character varying(6) NOT NULL,
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.locale_option OWNER TO pekkis;

--
-- Name: news_channel_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE news_channel_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.news_channel_id_seq OWNER TO pekkis;

--
-- Name: news_channel_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('news_channel_id_seq', 1, false);


--
-- Name: news_channel; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE news_channel (
    id integer DEFAULT nextval('news_channel_id_seq'::regclass) NOT NULL,
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


ALTER TABLE public.news_channel OWNER TO pekkis;

--
-- Name: news_item_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE news_item_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.news_item_id_seq OWNER TO pekkis;

--
-- Name: news_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('news_item_id_seq', 1, false);


--
-- Name: news_item; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE news_item (
    id integer DEFAULT nextval('news_item_id_seq'::regclass) NOT NULL,
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


ALTER TABLE public.news_item OWNER TO pekkis;

--
-- Name: page_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE page_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.page_id_seq OWNER TO pekkis;

--
-- Name: page_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('page_id_seq', 15, true);


--
-- Name: page; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE page (
    id integer DEFAULT nextval('page_id_seq'::regclass) NOT NULL,
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


ALTER TABLE public.page OWNER TO pekkis;

--
-- Name: page_global_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE page_global_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.page_global_id_seq OWNER TO pekkis;

--
-- Name: page_global_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('page_global_id_seq', 15, true);


--
-- Name: page_global; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE page_global (
    id integer DEFAULT nextval('page_global_id_seq'::regclass) NOT NULL
);


ALTER TABLE public.page_global OWNER TO pekkis;

--
-- Name: permission_folder_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE permission_folder_ugroup (
    folder_id integer NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.permission_folder_ugroup OWNER TO pekkis;

--
-- Name: permission_locale_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE permission_locale_ugroup (
    locale_locale character varying(6) NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.permission_locale_ugroup OWNER TO pekkis;

--
-- Name: permission_page_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE permission_page_ugroup (
    page_id integer NOT NULL,
    ugroup_id integer NOT NULL,
    permission smallint NOT NULL
);


ALTER TABLE public.permission_page_ugroup OWNER TO pekkis;

--
-- Name: shard; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE shard (
    id integer NOT NULL,
    name character varying(255) NOT NULL,
    module character varying(255) DEFAULT 'core'::character varying NOT NULL,
    controller character varying(255) DEFAULT 'index'::character varying NOT NULL,
    action character varying(255) DEFAULT 'index'::character varying NOT NULL,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public.shard OWNER TO pekkis;

--
-- Name: ugroup_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE ugroup_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.ugroup_id_seq OWNER TO pekkis;

--
-- Name: ugroup_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('ugroup_id_seq', 2, true);


--
-- Name: ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE ugroup (
    id integer DEFAULT nextval('ugroup_id_seq'::regclass) NOT NULL,
    name character varying(255) NOT NULL
);


ALTER TABLE public.ugroup OWNER TO pekkis;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: pekkis
--

CREATE SEQUENCE user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO pekkis;

--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: pekkis
--

SELECT pg_catalog.setval('user_id_seq', 2, true);


--
-- Name: user; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE "user" (
    id integer DEFAULT nextval('user_id_seq'::regclass) NOT NULL,
    email character varying(255) NOT NULL,
    passwd character(32) NOT NULL,
    firstname character varying(255) DEFAULT NULL::character varying,
    lastname character varying(255) DEFAULT NULL::character varying,
    status smallint DEFAULT 0::smallint NOT NULL
);


ALTER TABLE public."user" OWNER TO pekkis;

--
-- Name: user_option; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE user_option (
    user_id integer NOT NULL,
    identifier character varying(255) DEFAULT ''::character varying NOT NULL,
    strvalue character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.user_option OWNER TO pekkis;

--
-- Name: user_ugroup; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE user_ugroup (
    user_id integer NOT NULL,
    ugroup_id integer NOT NULL
);


ALTER TABLE public.user_ugroup OWNER TO pekkis;

--
-- Data for Name: application_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY application_option (identifier, strvalue) FROM stdin;
registered	1
installed	1
default_locale	fi_FI
google_analytics_id	UA-148617-9
\.


--
-- Data for Name: filelib_file; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY filelib_file (id, folder_id, mimetype, profile, size, name, link) FROM stdin;
2	2	image/jpeg	versioned	108773	kuva.jpg	manatees/kuva.jpg
\.


--
-- Data for Name: filelib_folder; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY filelib_folder (id, parent_id, name) FROM stdin;
1	\N	root
2	1	manatees
\.


--
-- Data for Name: form; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY form (id, name, description, status) FROM stdin;
1	Form	Test form	0
\.


--
-- Data for Name: form_field; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY form_field (id, form_id, type, order_id, title, mandatory, options) FROM stdin;
\.


--
-- Data for Name: formcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY formcontent (page_id, form_id, email_subject, email_from, email_to, redirect_page_id) FROM stdin;
\.


--
-- Data for Name: htmlcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY htmlcontent (page_id, block_id, content) FROM stdin;
6	1	<h1>Web</h1>\n<h2>Emerald</h2>\n<h2>Dr. Kobros</h2>\n<h2>Diktaattorip&ouml;rssi</h2>\n<h2>MHM Online</h2>\n<h2>Igglo / Oikotien asunnot 2.0</h2>\n<h2>eMedia eMember 2.0 "El Miembro"</h2>\n<h2>eMedia CMS 3.9</h2>\n<h2>L.E.D</h2>
3	1	<h1>Ty&ouml;t</h1>\n<p>Olen et&auml;isesti huvittunut ajatellessani asiaa. Olen viimeksi saanut isomman projektin valmiiksi vuonna 2000. Silloin julkaisin kolmannen ja viimeisen pelini, MHM 2000:n.</p>\n<p>T&auml;m&auml;n j&auml;lkeen olen ryhtynyt ainakin kirjoittamaan kirjaa, toteuttamaan julkaisuj&auml;rjestelm&auml;&auml; lopettamaan kaikki muut julkaisuj&auml;rjestelm&auml;t, sek&auml; tietenkin suunnittelemaan ja ohjelmoimaan ainakin kymment&auml; jatko-osaa arvostelumenestykseksi osoittautuneelle MHM 2000:lle.</p>\n<p>Valmista ei ole tullut, mutta seh&auml;n ei est&auml; yritt&auml;m&auml;st&auml;. Pienempi&auml; palasia olen yritt&auml;nyt haukkia sen verran, ett&auml; jotain riitt&auml;&auml; kerrottavaksi asti, ja p&auml;iv&auml;t&ouml;iss&auml; huhkiessa on sent&auml;&auml;n jotain ehtinyt valmiiksi asti.</p>
1	1	<div id="content-container">\n<h1>Neehk, neehk! (lue: lamantiini neehkuttaa)</h1>\n<p>Tervetuloa, veppikulkijani. Min&auml; olen illan is&auml;nt&auml; Mikko Forsstr&ouml;m, monille tutumpi lempinimell&auml;ni <a href="http://beta.lamantiini.com/projects/story-of-pekkis">Pekkis</a>, ja n&auml;m&auml; ovat kotskasivuni. Edellinen kotskaporttaali n&auml;ivettyi kuoliaaksi vuoden 2004 syksyll&auml;, joten viimeisest&auml; onkin jo aikaa.</p>\n<p><a href="http://fi.wikipedia.org/wiki/Lamantiini">Lamantiini</a> (trichechus manatus) puolestaan on merten kultainen noutaja, luonnostaan letke&auml; l&ouml;tk&auml;le. T&auml;m&auml; el&auml;imist&auml; jaloin tunnetaan my&ouml;s manaattina, joka puolestaan ei tarkoita merilehm&auml;&auml;. Lamantiinin h&auml;nt&auml; on melan muotoinen, kun taas merilehmien h&auml;nn&auml;t ovat haaroittuneita. Ero on pieni, mutta sit&auml;kin merkitt&auml;v&auml;mpi.</p>\n<p><a href="http://beta.lamantiini.com/projects/web">PHP- ja Internet-ekspertti</a>, <a href="http://beta.lamantiini.com/projects/games">pelintekij&auml;</a>, <a href="http://beta.lamantiini.com/projects/writings">luova kirjoittaja</a>, <a href="http://beta.lamantiini.com/projects/dictatorship">tuleva diktaattori</a> ja <a href="http://beta.lamantiini.com/projects/manatee-art">armoitettu lamantiinikansantaiteilija</a>. Olen yritt&auml;nyt monella saralla, vaihtelevin tuloksin. T&auml;m&auml; kotskaporttaali kaivaa sekametelisopasta syv&auml;llisi&auml;  merkityksi&auml;, joita siin&auml; ei ole, ja pist&auml;&auml; niin kutsutun el&auml;m&auml;ni kertaheitolla pakettiin. Tomusokeria p&auml;&auml;lle ja hyv&auml;lt' n&auml;ytt&auml;&auml;.</p>\n</div>
14	1	<div id="content-container">\n<h1>Lamantiinitaide</h1>\n<p>Lamantiinitaidetta ei kannata kahlita karsinaan, johon mahtuvat vain lamantiinit. Lamantiinitaide merkitsee laajemmassa kontekstissa kaikkea kuvataidetta, jonka olen itsest&auml;ni ulos pulauttanut.</p>\n<p>Lamantiinitaidetta ei saa miss&auml;&auml;n nimess&auml; sotkea naivismiin. Itse asiassa on parempi, jos et lokeroi lamantiinitaidetta yhteenk&auml;&auml;n ennest&auml;&auml;n tuntemaasi taiteen tyylisuuntaukseen. T&auml;rkeint&auml; on muistaa, ett&auml; lamantiinitaide on tehty "tosissaan". Lamantiinit edustavat kuvallisen ilmaisukykyni huippua. Olen harjoitellut vuosia p&auml;&auml;st&auml;kseni t&auml;lle tasolle, enk&auml; nyt tarkoita ik&auml;vuosia nelj&auml;st&auml; seitsem&auml;&auml;n.</p>\n</div>
\.


--
-- Data for Name: locale; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY locale (locale, page_start) FROM stdin;
en_US	\N
fi_FI	\N
\.


--
-- Data for Name: locale_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY locale_option (locale_locale, identifier, strvalue) FROM stdin;
en_US	open	0
fi_FI	title	Lamantiini 2k10
fi_FI	page_start	1
fi_FI	open	1
en_US	title	Lamantine 2k10
en_US	page_start	2
\.


--
-- Data for Name: news_channel; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY news_channel (id, page_id, items_per_page, link_readmore, allow_syndication, default_months_valid, title, description, locale, copyright, managing_editor, webmaster, category, ttl, skip_hours, skip_days, status) FROM stdin;
\.


--
-- Data for Name: news_item; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY news_item (id, news_channel_id, title, description, article, author, category, comments, enclosure, valid_start, valid_end, status) FROM stdin;
\.


--
-- Data for Name: page; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY page (id, global_id, locale, parent_id, order_id, layout, title, beautifurl, path, shard_id, visibility, cache_seconds, status, redirect_id) FROM stdin;
2	2	en_US	\N	1	Default	Welcome	en_US/welcome	[2]	1	1	0	0	\N
1	1	fi_FI	\N	1	Default	Tervetuloa!	fi_FI/tervetuloa	[1]	1	1	0	0	\N
7	7	fi_FI	\N	2	Default	Tietoa	fi_FI/tietoa	[7]	1	1	0	0	8
8	8	fi_FI	7	3	Default	Portfolio	fi_FI/tietoa/portfolio	[7];[8]	1	1	0	0	\N
3	3	fi_FI	\N	4	Default	Työt	fi_FI/tyot	[3]	1	1	0	0	\N
6	6	fi_FI	3	5	Default	Web	fi_FI/tyot/web	[3];[6]	1	1	0	0	\N
4	4	fi_FI	3	6	Default	Pelit	fi_FI/tyot/pelit	[3];[4]	1	1	0	0	\N
5	5	fi_FI	3	7	Default	Kirjoitukset	fi_FI/tyot/kirjoitukset	[3];[5]	1	1	0	0	\N
15	15	fi_FI	\N	9	Default	Blogi	fi_FI/blogi	[15]	1	1	0	0	\N
14	14	fi_FI	3	8	Default	Lamantiinitaide	fi_FI/tyot/lamantiinitaide	[3];[14]	1	1	0	0	\N
\.


--
-- Data for Name: page_global; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY page_global (id) FROM stdin;
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
11
12
13
14
15
\.


--
-- Data for Name: permission_folder_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY permission_folder_ugroup (folder_id, ugroup_id, permission) FROM stdin;
2	1	4
2	2	7
\.


--
-- Data for Name: permission_locale_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY permission_locale_ugroup (locale_locale, ugroup_id, permission) FROM stdin;
en_US	2	7
fi_FI	1	4
fi_FI	2	7
\.


--
-- Data for Name: permission_page_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY permission_page_ugroup (page_id, ugroup_id, permission) FROM stdin;
1	1	4
1	2	7
2	1	4
2	2	7
3	1	4
3	2	7
4	1	4
4	2	7
5	1	4
5	2	7
6	1	4
6	2	7
8	1	4
8	2	7
7	1	4
7	2	7
14	2	7
15	1	4
15	2	7
\.


--
-- Data for Name: shard; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY shard (id, name, module, controller, action, status) FROM stdin;
1	Html	core	html-content	index	3
2	Form	core	form-content	index	3
3	News	core	news	index	3
\.


--
-- Data for Name: ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY ugroup (id, name) FROM stdin;
1	Anonymous
2	Root
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY "user" (id, email, passwd, firstname, lastname, status) FROM stdin;
1	puhemies@diktaattoriporssi.com	b30e8b46fb8b625d010b02e8d833fb15	\N	\N	1
\.


--
-- Data for Name: user_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY user_option (user_id, identifier, strvalue) FROM stdin;
\.


--
-- Data for Name: user_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY user_ugroup (user_id, ugroup_id) FROM stdin;
1	2
\.


--
-- Name: application_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY application_option
    ADD CONSTRAINT application_option_pkey PRIMARY KEY (identifier);


--
-- Name: filelib_file_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY filelib_file
    ADD CONSTRAINT filelib_file_name_key UNIQUE (name, folder_id);


--
-- Name: filelib_file_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY filelib_file
    ADD CONSTRAINT filelib_file_pkey PRIMARY KEY (id);


--
-- Name: filelib_folder_parent_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY filelib_folder
    ADD CONSTRAINT filelib_folder_parent_id_key UNIQUE (parent_id, name);


--
-- Name: filelib_folder_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY filelib_folder
    ADD CONSTRAINT filelib_folder_pkey PRIMARY KEY (id);


--
-- Name: form_field_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY form_field
    ADD CONSTRAINT form_field_pkey PRIMARY KEY (id);


--
-- Name: form_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_name_key UNIQUE (name);


--
-- Name: form_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY form
    ADD CONSTRAINT form_pkey PRIMARY KEY (id);


--
-- Name: formcontent_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY formcontent
    ADD CONSTRAINT formcontent_pkey PRIMARY KEY (page_id);


--
-- Name: htmlcontent_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY htmlcontent
    ADD CONSTRAINT htmlcontent_pkey PRIMARY KEY (page_id, block_id);


--
-- Name: locale_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY locale_option
    ADD CONSTRAINT locale_option_pkey PRIMARY KEY (locale_locale, identifier);


--
-- Name: locale_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY locale
    ADD CONSTRAINT locale_pkey PRIMARY KEY (locale);


--
-- Name: news_channel_page_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY news_channel
    ADD CONSTRAINT news_channel_page_id_key UNIQUE (page_id);


--
-- Name: news_channel_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY news_channel
    ADD CONSTRAINT news_channel_pkey PRIMARY KEY (id);


--
-- Name: news_item_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY news_item
    ADD CONSTRAINT news_item_pkey PRIMARY KEY (id);


--
-- Name: page_global_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_global_id_key UNIQUE (global_id, locale);


--
-- Name: page_global_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY page_global
    ADD CONSTRAINT page_global_pkey PRIMARY KEY (id);


--
-- Name: page_parent_id_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_parent_id_key UNIQUE (parent_id, title);


--
-- Name: page_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_pkey PRIMARY KEY (id);


--
-- Name: permission_folder_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY permission_folder_ugroup
    ADD CONSTRAINT permission_folder_ugroup_pkey PRIMARY KEY (folder_id, ugroup_id);


--
-- Name: permission_locale_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY permission_locale_ugroup
    ADD CONSTRAINT permission_locale_ugroup_pkey PRIMARY KEY (locale_locale, ugroup_id);


--
-- Name: permission_page_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY permission_page_ugroup
    ADD CONSTRAINT permission_page_ugroup_pkey PRIMARY KEY (page_id, ugroup_id);


--
-- Name: shard_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY shard
    ADD CONSTRAINT shard_name_key UNIQUE (name);


--
-- Name: shard_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY shard
    ADD CONSTRAINT shard_pkey PRIMARY KEY (id);


--
-- Name: ugroup_name_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY ugroup
    ADD CONSTRAINT ugroup_name_key UNIQUE (name);


--
-- Name: ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY ugroup
    ADD CONSTRAINT ugroup_pkey PRIMARY KEY (id);


--
-- Name: user_email_key; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_email_key UNIQUE (email);


--
-- Name: user_option_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY user_option
    ADD CONSTRAINT user_option_pkey PRIMARY KEY (user_id, identifier);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY "user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: user_ugroup_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY user_ugroup
    ADD CONSTRAINT user_ugroup_pkey PRIMARY KEY (user_id, ugroup_id);


--
-- Name: page_beautifurl_idx; Type: INDEX; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE UNIQUE INDEX page_beautifurl_idx ON page USING btree (beautifurl);


--
-- Name: filelib_file_folder_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY filelib_file
    ADD CONSTRAINT filelib_file_folder_id_fkey FOREIGN KEY (folder_id) REFERENCES filelib_folder(id) ON UPDATE CASCADE;


--
-- Name: filelib_folder_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY filelib_folder
    ADD CONSTRAINT filelib_folder_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES filelib_folder(id) ON UPDATE CASCADE;


--
-- Name: form_field_form_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY form_field
    ADD CONSTRAINT form_field_form_id_fkey FOREIGN KEY (form_id) REFERENCES form(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formcontent_form_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY formcontent
    ADD CONSTRAINT formcontent_form_id_fkey FOREIGN KEY (form_id) REFERENCES form(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: formcontent_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY formcontent
    ADD CONSTRAINT formcontent_page_id_fkey FOREIGN KEY (page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: formcontent_redirect_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY formcontent
    ADD CONSTRAINT formcontent_redirect_page_id_fkey FOREIGN KEY (redirect_page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: htmlcontent_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY htmlcontent
    ADD CONSTRAINT htmlcontent_page_id_fkey FOREIGN KEY (page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: locale_option_locale_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY locale_option
    ADD CONSTRAINT locale_option_locale_locale_fkey FOREIGN KEY (locale_locale) REFERENCES locale(locale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: locale_page_start_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY locale
    ADD CONSTRAINT locale_page_start_fkey FOREIGN KEY (page_start) REFERENCES page(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: news_channel_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY news_channel
    ADD CONSTRAINT news_channel_page_id_fkey FOREIGN KEY (page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: news_item_news_channel_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY news_item
    ADD CONSTRAINT news_item_news_channel_id_fkey FOREIGN KEY (news_channel_id) REFERENCES news_channel(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: page_global_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_global_id_fkey FOREIGN KEY (global_id) REFERENCES page_global(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: page_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_locale_fkey FOREIGN KEY (locale) REFERENCES locale(locale) ON UPDATE CASCADE;


--
-- Name: page_parent_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_parent_id_fkey FOREIGN KEY (parent_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: page_redirect_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_redirect_id_fkey FOREIGN KEY (redirect_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: page_shard_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY page
    ADD CONSTRAINT page_shard_id_fkey FOREIGN KEY (shard_id) REFERENCES shard(id) ON UPDATE CASCADE;


--
-- Name: permission_folder_ugroup_folder_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_folder_ugroup
    ADD CONSTRAINT permission_folder_ugroup_folder_id_fkey FOREIGN KEY (folder_id) REFERENCES filelib_folder(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permission_folder_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_folder_ugroup
    ADD CONSTRAINT permission_folder_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permission_locale_ugroup_locale_locale_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_locale_ugroup
    ADD CONSTRAINT permission_locale_ugroup_locale_locale_fkey FOREIGN KEY (locale_locale) REFERENCES locale(locale) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permission_locale_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_locale_ugroup
    ADD CONSTRAINT permission_locale_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permission_page_ugroup_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_page_ugroup
    ADD CONSTRAINT permission_page_ugroup_page_id_fkey FOREIGN KEY (page_id) REFERENCES page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: permission_page_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY permission_page_ugroup
    ADD CONSTRAINT permission_page_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_option_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY user_option
    ADD CONSTRAINT user_option_user_id_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_ugroup_ugroup_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY user_ugroup
    ADD CONSTRAINT user_ugroup_ugroup_id_fkey FOREIGN KEY (ugroup_id) REFERENCES ugroup(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: user_ugroup_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY user_ugroup
    ADD CONSTRAINT user_ugroup_user_id_fkey FOREIGN KEY (user_id) REFERENCES "user"(id) ON UPDATE CASCADE ON DELETE CASCADE;


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

