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

SELECT pg_catalog.setval('emerald_activity_id_seq', 7, true);


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
-- Name: emerald_customcontent; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_customcontent (
    page_id integer NOT NULL,
    block_id integer NOT NULL,
    module character varying(255),
    controller character varying(255),
    action character varying(255),
    params character varying(1000)
);


ALTER TABLE public.emerald_customcontent OWNER TO pekkis;

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

SELECT pg_catalog.setval('emerald_filelib_file_id_seq', 1, true);


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

SELECT pg_catalog.setval('emerald_filelib_folder_id_seq', 2, true);


--
-- Name: emerald_filelib_folder; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_filelib_folder (
    id integer DEFAULT nextval('emerald_filelib_folder_id_seq'::regclass) NOT NULL,
    parent_id integer,
    name character varying(255) NOT NULL,
    visible smallint DEFAULT 1 NOT NULL
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
    status smallint DEFAULT (0)::smallint NOT NULL
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
    order_id smallint DEFAULT (0)::smallint NOT NULL,
    title character varying(255) DEFAULT NULL::character varying,
    mandatory smallint DEFAULT (0)::smallint NOT NULL,
    options text
);


ALTER TABLE public.emerald_form_field OWNER TO pekkis;

--
-- Name: emerald_formcontent; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_formcontent (
    page_id integer NOT NULL,
    form_id integer,
    email_subject character varying(255) NOT NULL,
    email_from character varying(255) NOT NULL,
    email_to character varying(255) NOT NULL,
    redirect_page_id integer NOT NULL
);


ALTER TABLE public.emerald_formcontent OWNER TO pekkis;

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

SELECT pg_catalog.setval('emerald_news_channel_id_seq', 1, true);


--
-- Name: emerald_news_channel; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_news_channel (
    id integer DEFAULT nextval('emerald_news_channel_id_seq'::regclass) NOT NULL,
    page_id integer NOT NULL,
    items_per_page smallint DEFAULT (10)::smallint NOT NULL,
    link_readmore character varying(255) NOT NULL,
    allow_syndication smallint DEFAULT (1)::smallint NOT NULL,
    default_months_valid smallint DEFAULT (12)::smallint,
    title character varying(255) NOT NULL,
    description text,
    locale character varying(6) DEFAULT NULL::character varying,
    copyright character varying(255) DEFAULT NULL::character varying,
    managing_editor character varying(255) DEFAULT NULL::character varying,
    webmaster character varying(255) DEFAULT NULL::character varying,
    category character varying(255) DEFAULT NULL::character varying,
    ttl smallint DEFAULT (60)::smallint NOT NULL,
    skip_hours character varying(255) DEFAULT NULL::character varying,
    skip_days character varying(255) DEFAULT NULL::character varying,
    status smallint DEFAULT (0)::smallint NOT NULL
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

SELECT pg_catalog.setval('emerald_news_item_id_seq', 1, true);


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
    status smallint DEFAULT (0)::smallint NOT NULL
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

SELECT pg_catalog.setval('emerald_page_id_seq', 10, true);


--
-- Name: emerald_page; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_page (
    id integer DEFAULT nextval('emerald_page_id_seq'::regclass) NOT NULL,
    global_id integer NOT NULL,
    locale character varying(6) NOT NULL,
    parent_id integer,
    order_id smallint DEFAULT (0)::smallint NOT NULL,
    layout character varying(255) DEFAULT 'Default'::character varying,
    title character varying(255) NOT NULL,
    beautifurl character varying(1000) DEFAULT NULL::character varying,
    path character varying(255),
    shard_id integer NOT NULL,
    visibility smallint DEFAULT (1)::smallint NOT NULL,
    cache_seconds integer DEFAULT 0 NOT NULL,
    redirect_id integer,
    status smallint DEFAULT (0)::smallint NOT NULL
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
    namespace character varying(255) DEFAULT 'EmCore'::character varying NOT NULL,
    name character varying(255) NOT NULL,
    module character varying(255) DEFAULT 'core'::character varying NOT NULL,
    controller character varying(255) DEFAULT 'index'::character varying NOT NULL,
    action character varying(255) DEFAULT 'index'::character varying NOT NULL,
    status smallint DEFAULT (0)::smallint NOT NULL
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

SELECT pg_catalog.setval('emerald_ugroup_id_seq', 3, true);


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

SELECT pg_catalog.setval('emerald_user_id_seq', 2, true);


--
-- Name: emerald_user; Type: TABLE; Schema: public; Owner: pekkis; Tablespace: 
--

CREATE TABLE emerald_user (
    id integer DEFAULT nextval('emerald_user_id_seq'::regclass) NOT NULL,
    email character varying(255) NOT NULL,
    passwd character(32) NOT NULL,
    firstname character varying(255) DEFAULT NULL::character varying,
    lastname character varying(255) DEFAULT NULL::character varying,
    status smallint DEFAULT (0)::smallint NOT NULL
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
3	administration	expose
1	administration	edit_activities
2	administration	clear_caches
4	administration	edit_users
5	administration	edit_locales
6	administration	edit_forms
7	administration	edit_options
\.


--
-- Data for Name: emerald_application_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_application_option (identifier, strvalue) FROM stdin;
installed	1
default_locale	en_US
\.


--
-- Data for Name: emerald_customcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_customcontent (page_id, block_id, module, controller, action, params) FROM stdin;
\.


--
-- Data for Name: emerald_filelib_file; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_filelib_file (id, folder_id, mimetype, profile, size, name, link) FROM stdin;
1	2	image/jpeg	versioned	173280	omnomnomnom56.jpg	images/omnomnomnom56.jpg
\.


--
-- Data for Name: emerald_filelib_folder; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_filelib_folder (id, parent_id, name, visible) FROM stdin;
1	\N	root	1
2	1	images	1
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
-- Data for Name: emerald_formcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_formcontent (page_id, form_id, email_subject, email_from, email_to, redirect_page_id) FROM stdin;
\.


--
-- Data for Name: emerald_htmlcontent; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_htmlcontent (page_id, block_id, content) FROM stdin;
7	1	<h1 class="title">License</h1>\n<p>Emerald is licensed under the new BSD license.</p>\n<p>&nbsp;</p>\n<hr />\n<p>Copyright (c) 2009-2010 Mikko Forsstr&ouml;m<br />All rights reserved.</p>\n<p>Redistribution and use in source and binary forms, with or without modification,<br />are permitted provided that the following conditions are met:</p>\n<ul>\n<li>Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.</li>\n<li>Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.</li>\n<li>Neither the name of Mikko Forsstr&ouml;m nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.</li>\n</ul>\n<p>THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;&nbsp; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.</p>
6	1	<h1 class="title">Credits</h1>\n<h2>Development Lead</h2>\n<ul>\n<li>Mikko Forsstr&ouml;m (Pekkis)</li>\n</ul>\n<h2>Contributors</h2>\n<ul>\n<li>Mikko Hirvonen</li>\n<li>Petri Mahanen</li>\n<li>Henri Vesala</li>\n<li>Jorma Tuomainen</li>\n<li>Petri Heinonen</li>\n<li>Mikko H&auml;m&auml;l&auml;inen</li>\n<li>Eetu Hypp&ouml;nen</li>\n</ul>\n<h2>Corporate godfathers</h2>\n<ul>\n<li><a href="http://brainalliance.com" target="_blank">Soprano Brain Alliance Ltd.</a></li>\n<li><a href="http://www.only4fun.org" target="_blank">Foolabs Ltd.</a></li>\n</ul>
1	3	<h2><strong>Emerald</strong> in a nutshell</h2>\n<ul>\n<li>Built upon Zend Framework</li>\n<li>Customizable and extensible</li>\n<li>Easy to use</li>\n<li>Fun to code</li>\n<li>Super fast and hyper scalable!</li>\n<li>Utilizes the patented Beautifurls&reg;</li>\n<li>Transactions and referential integrity</li>\n<li>Free software!</li>\n</ul>
4	1	<p>Features</p>\n<p>Locales (countries &amp; languages)</p>\n<p>Sitemap</p>\n<p>User &amp; group management</p>\n<p>File library</p>\n<p>HTML blocks</p>\n<p>News pages</p>\n<p>Forms</p>\n<p>Options</p>
3	1	<h1 class="title">system requirements</h1>\n<p>Emerald 3.0.0 has the following system requirements.</p>\n<ul>\n<li>Operating system with symbolic link support (Linux, Mac OS X tested to work)         \n<ul>\n<li>No, it does not work on Windows at this time. Emerald uses symbolic links extensively. Windows Vista and 7 should be doable, though.</li>\n</ul>\n</li>\n<li>HTTP server (tested with Apache, Nginx + Apache)           \n<ul>\n<li>Emerald must be installed safely <em>outside</em> web scope.</li>\n<li>Rewrite engine required. Ugly urls are not supported.</li>\n</ul>\n</li>\n<li>PHP 5.2.x+ (PHP 5.3.x will be required by Emerald 3.1 series)             \n<ul>\n<li>Extensions required by Zend Framework: refer Zend Framework's requirements.</li>\n<li>Additional extensions required: Imagick, Fileinfo.</li>\n<li>Recommended extensions: APC, Memcache (required for Emerald to be super fast).</li>\n</ul>\n</li>\n<li>Zend Framework 1.10.x         \n<ul>\n<li>1.10.4 included</li>\n</ul>\n</li>\n<li>PostgreSQL or MySQL / MariaDB compatible database (PostgreSQL 8.3, PostgreSQL 8.4, MySQL 5.0, MySQL 5.1 tested)       \n<ul>\n<li>MySQL must be used with a transaction / referential integrity - capable storage engine such as InnoDB<strong>.</strong> MariaDB should work, too.</li>\n</ul>\n</li>\n<li>Browser       \n<ul>\n<li>At this point it would be wise to use Firefox or a webkit browser (Chrome / Safari). No information&nbsp; is available concerning Internet Explorer at this point, but IE 6 will be supported as a client only (no admin).</li>\n</ul>\n</li>\n</ul>
1	1	<h1 class="title">Manage content with <strong>confidence</strong></h1>\n<p>Welcome to Emerald, a content management system built upon Zend Framework.</p>\n<p><img class="right" title="This is a manatee. It has nothing to do with Emerald, but I like manatees." src="/em-filelib/file/render/id/1/version/mini" alt="Manatee" />Why another CMS? A reasonable question. There's a bunch of already established big open source players: Drupal, eZ Publish, WordPress, Joomla, and on and on. There's also a gazillion proprietary ones, just about every web sweat shop wants to sell it's own solution. Hell, there's even CMS's made with Zend Framework these days: Digitalus and TomatoCMS come to mind.</p>\n<p>But hey, what can one do? Everyone just <strong>has to have </strong>his/her own CMS. I think CMS building is embedded in the web developers' genes. Emerald is like the sixth or seventh (or something like that) one I've done.</p>\n<p>Seriously, though. I think there is a niche market available. A plug-in CMS companion for real web applications. This is where Emerald fits in.</p>\n<p>Emerald is built with Zend Framework, which is a great tool for building custom web applications. Furthermore, Emerald runs <em>within Zend Framework's standard application flow.</em> You can share all resources between Emerald and the rest of your app. You can mix and match functionality. You just grab Emerald, bootstrap it to your new or existing app, and voil&aacute; -- suddenly you have content management. And every web application, custom or not, needs content management!</p>\n<p>Of course Emerald has other ambitions, too, and it has a solid base as a stand-alone product, but the project's main goal is just that simple: <em>to be an easily integratable, simple-to-use and fun-to-code CMS companion for web applications built with Zend Framework</em>. And yes, don't mind you asking, it's gotta be the fastest kid on the block.</p>
10	1	<h1 class="title">Technology</h1>\n<h2>Server side</h2>\n<p>Emerald utilizes the Zend Framework. It uses default functionality where it's possible, and extends Zend with Emerald specific features.</p>\n<p>The notable exceptions are a few application resources and Emerald_Cache_Manager, which compete with Zend's implementations. These and a couple of other stuff are things which I found insufficient for Emerald.</p>\n<h2>Client side</h2>\n<p>Emerald utilizes the jQuery and Underscore libraries as the base of it's JavaScript framework.</p>
\.


--
-- Data for Name: emerald_locale; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_locale (locale, page_start) FROM stdin;
en_US	1
\.


--
-- Data for Name: emerald_locale_option; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_locale_option (locale_locale, identifier, strvalue) FROM stdin;
en_US	title	Emerald - Content management built upon Zend Framework
en_US	page_start	1
en_US	beautifurler	
\.


--
-- Data for Name: emerald_news_channel; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_news_channel (id, page_id, items_per_page, link_readmore, allow_syndication, default_months_valid, title, description, locale, copyright, managing_editor, webmaster, category, ttl, skip_hours, skip_days, status) FROM stdin;
1	5	10	Read more	1	12	Emerald News	Latest buzz surrounding Emerald	\N	\N	\N	\N	\N	60	\N	\N	0
\.


--
-- Data for Name: emerald_news_item; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_news_item (id, news_channel_id, title, description, article, author, category, comments, enclosure, valid_start, valid_end, status) FROM stdin;
1	1	Emerald 3.0.0 beta 1 released	Release news	<p>Release article</p>	\N	\N	\N	\N	2010-04-29 00:00:00	2011-04-29 00:00:00	1
\.


--
-- Data for Name: emerald_page; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_page (id, global_id, locale, parent_id, order_id, layout, title, beautifurl, path, shard_id, visibility, cache_seconds, redirect_id, status) FROM stdin;
4	4	en_US	2	1	Default	Features	en_US/about/features	[2];[4]	1	1	0	\N	0
3	3	en_US	2	2	Default	System Requirements	en_US/about/system-requirements	[2];[3]	1	1	0	\N	0
1	1	en_US	\N	1	Front	Home	en_US/home	[1]	1	1	0	\N	0
8	8	en_US	2	5	Default	Roadmap	en_US/about/roadmap	[2];[8]	1	1	0	\N	0
2	2	en_US	\N	2	Default	About	en_US/about	[2]	1	1	0	4	0
7	7	en_US	2	6	Default	License	en_US/about/license	[2];[7]	1	1	0	\N	0
9	9	en_US	2	4	Default	History	en_US/about/history	[2];[9]	1	1	0	\N	0
10	10	en_US	2	3	Default	Technology	en_US/about/technology	[2];[10]	1	1	0	\N	0
6	6	en_US	2	7	Default	Credits	en_US/about/credits	[2];[6]	1	1	0	\N	0
5	5	en_US	\N	3	Default	News	en_US/news	[5]	3	1	0	\N	0
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
2	2
1	2
6	2
5	2
7	2
4	2
3	2
\.


--
-- Data for Name: emerald_permission_folder_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_folder_ugroup (folder_id, ugroup_id, permission) FROM stdin;
2	1	4
2	2	7
\.


--
-- Data for Name: emerald_permission_locale_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_locale_ugroup (locale_locale, ugroup_id, permission) FROM stdin;
en_US	1	4
en_US	2	7
\.


--
-- Data for Name: emerald_permission_page_ugroup; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_permission_page_ugroup (page_id, ugroup_id, permission) FROM stdin;
1	1	4
1	2	7
3	1	4
3	2	7
4	1	4
4	2	7
2	1	4
2	2	7
5	1	4
5	2	7
6	1	4
6	2	7
7	1	4
7	2	7
8	1	4
8	2	7
9	1	4
9	2	7
10	1	4
10	2	7
\.


--
-- Data for Name: emerald_shard; Type: TABLE DATA; Schema: public; Owner: pekkis
--

COPY emerald_shard (id, namespace, name, module, controller, action, status) FROM stdin;
1	EmCore	Html	em-core	html-content	index	3
2	EmCore	Form	em-core	form-content	index	3
3	EmCore	News	em-core	news	index	3
4	EmCore	Custom	em-core	custom-content	index	3
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
-- Name: emerald_customcontent_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_customcontent
    ADD CONSTRAINT emerald_customcontent_pkey PRIMARY KEY (page_id, block_id);


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
-- Name: emerald_formcontent_pkey; Type: CONSTRAINT; Schema: public; Owner: pekkis; Tablespace: 
--

ALTER TABLE ONLY emerald_formcontent
    ADD CONSTRAINT emerald_formcontent_pkey PRIMARY KEY (page_id);


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
-- Name: emerald_customcontent_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_customcontent
    ADD CONSTRAINT emerald_customcontent_page_id_fkey FOREIGN KEY (page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


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
-- Name: emerald_formcontent_form_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_formcontent
    ADD CONSTRAINT emerald_formcontent_form_id_fkey FOREIGN KEY (form_id) REFERENCES emerald_form(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- Name: emerald_formcontent_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_formcontent
    ADD CONSTRAINT emerald_formcontent_page_id_fkey FOREIGN KEY (page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: emerald_formcontent_redirect_page_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_formcontent
    ADD CONSTRAINT emerald_formcontent_redirect_page_id_fkey FOREIGN KEY (redirect_page_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE CASCADE;


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
-- Name: emerald_page_redirect_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: pekkis
--

ALTER TABLE ONLY emerald_page
    ADD CONSTRAINT emerald_page_redirect_id_fkey FOREIGN KEY (redirect_id) REFERENCES emerald_page(id) ON UPDATE CASCADE ON DELETE SET NULL;


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

