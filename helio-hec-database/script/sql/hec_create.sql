
-- # INAF - Trieste Astronomical Observatory
-- creation script
-- by M.Jurcev,A.Santin last rev. 09-May-2005

-- User creation
DROP USER apache;
CREATE USER apache;
DROP USER root;
CREATE USER root CREATEUSER;

-- Creation of catalogue tables
DROP TABLE sgas_event;
CREATE TABLE sgas_event (
	sgs_id			SERIAL,
	ntime_start		TIMESTAMP,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
	ntime_end		TIMESTAMP,
	nar				INTEGER,
	latitude		FLOAT,
	longitude		FLOAT,
	long_carr		FLOAT,
	xray_class		VARCHAR(10),
	optical_class	VARCHAR(10),
	radio_245mhz	INTEGER,
	radio_10cm		INTEGER,
	radio_sweep_ii	BOOLEAN,
	radio_sweep_iv	BOOLEAN,
	swf				VARCHAR(10),
	PRIMARY KEY (sgs_id)
);

DROP TABLE noaa_proton_event;
CREATE TABLE noaa_proton_event (
	npe_id			SERIAL,
	time_start 		TIMESTAMP,
	time_peak 		TIMESTAMP,
	nar				INTEGER,
	latitude		FLOAT,
	longitude		FLOAT,
	proton_flux		FLOAT,
	assoc_cme		VARCHAR(20),
	assoc_flare_pk	TIMESTAMP,
	xray_class		VARCHAR(10),
	optical_class	VARCHAR(10),
	PRIMARY KEY (npe_id)
);

DROP TABLE hessi_flare;
CREATE TABLE hessi_flare (
	hef_id			SERIAL,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
	nar				INTEGER,
	x_arcsec		FLOAT,
	y_arcsec		FLOAT,
	radial_arcsec	FLOAT,
	duration 		INTEGER,
	count_sec_peak	INTEGER,
	total_count		INTEGER,
	energy_kev		INTEGER,
	flare_number	INTEGER,
	PRIMARY KEY (hef_id)
);

DROP TABLE lasco_cme_list;
CREATE TABLE lasco_cme_list (
	lcl_id		SERIAL,
	time_start	TIMESTAMP,
	pa_central	FLOAT,
	description	VARCHAR(512),
	PRIMARY KEY (lcl_id)
);

DROP TABLE lasco_cme_cat;
CREATE TABLE lasco_cme_cat (
	lcc_id			SERIAL,
	time_start		TIMESTAMP,
	time_end 	TIMESTAMP,
	pa_central		FLOAT,
	pa_measure		FLOAT,
	pa_width		FLOAT,
	linear_speed	FLOAT,
	speed2_init		FLOAT,
	speed2_final	FLOAT,
	speed2_20r		FLOAT,
	acceleration	FLOAT,
	PRIMARY KEY (lcc_id)
);

DROP TABLE sidc_sunspot_number;
CREATE TABLE sidc_sunspot_number (
	ssn_id		SERIAL,
	time_start 	TIMESTAMP,
	time_end 	TIMESTAMP,
	ssn			FLOAT,
	PRIMARY KEY (ssn_id)
);

DROP TABLE drao_10cm_flux;
CREATE TABLE drao_10cm_flux (
	sfm_id			SERIAL,
	time_start 		TIMESTAMP,
	time_end 		TIMESTAMP,
	sfu_observed	FLOAT,
	sfu_adjusted	FLOAT,
	sfu_series_d	FLOAT,
	PRIMARY KEY (sfm_id)
);

DROP TABLE dsd_list;
CREATE TABLE dsd_list (
	dsd_id		SERIAL,
	time_start		TIMESTAMP,
	radio_10cm		INTEGER,
	sesc_ssn		INTEGER,
	ss_area			INTEGER,
	new_regions		INTEGER,
	stan_smf		INTEGER,
	xray_bkg		VARCHAR(10),
	c_flares		INTEGER,
	m_flares		INTEGER,
	x_flares		INTEGER,
	opts_flares		INTEGER,
	opt1_flares		INTEGER,
	opt2_flares		INTEGER,
	opt3_flares		INTEGER,
	PRIMARY KEY (dsd_id)
);

DROP TABLE yohkoh_flare_list;
CREATE TABLE yohkoh_flare_list (
	yfc_id			SERIAL,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
	nar				INTEGER,
	latitude		FLOAT,
	longitude		FLOAT,
	xray_class		VARCHAR(10),
	optical_class	VARCHAR(10),
	hxt_lo			INTEGER,
	hxt_m1			INTEGER,
	hxt_m2			INTEGER,
	hxt_hi			INTEGER,
	rem				VARCHAR(3),
	yoh_event		INTEGER,
	PRIMARY KEY (yfc_id)
);

DROP TABLE srs_list;
CREATE TABLE srs_list (
	srs_id			SERIAL,
	time_start		TIMESTAMP,
	nar				INTEGER,
	latitude		FLOAT,
	longitude		FLOAT,
	long_carr		FLOAT,
	area			FLOAT,
	zurich_class	VARCHAR(2),
	p_value			VARCHAR(2),
	c_value			VARCHAR(2),
	long_extent		FLOAT,
	n_spots			INTEGER,
	mag_class		VARCHAR(20),
	region_type		VARCHAR(30),
	PRIMARY KEY (srs_id)
);

DROP TABLE bas_magnetic_storms;
CREATE TABLE bas_magnetic_storms (
	bms_id			SERIAL,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
	dst				INTEGER,
	hduration		INTEGER,
	PRIMARY KEY (bms_id)
);

DROP TABLE goes_xray_flare;
CREATE TABLE goes_xray_flare (
	goes_id			SERIAL,
	ntime_start		TIMESTAMP,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
	ntime_end		TIMESTAMP,
    nar				INTEGER,
	latitude		FLOAT,
    longitude		FLOAT,
	long_carr		FLOAT,
	xray_class		VARCHAR(10),
    optical_class	VARCHAR(10),
    PRIMARY KEY (goes_id));

DROP TABLE soho_camp;
CREATE TABLE soho_camp (
	soho_id			SERIAL,
	time_start		TIMESTAMP,
	time_end		TIMESTAMP,
    sohoc_num		INTEGER,
    sohoc_name	VARCHAR(128),
    sohoc_type	VARCHAR(384),
    sohoc_obj	VARCHAR(2048),
    sohoc_coord	VARCHAR(128),
    sohoc_part	VARCHAR(3328),
    sohoc_comm	VARCHAR(384),
    PRIMARY KEY (soho_id)
);

DROP TABLE kso_flare;
CREATE TABLE kso_flare (
	kso_id			SERIAL,
	time_start		TIMESTAMP,
	time_start_m	VARCHAR(2),
	time_peak		TIMESTAMP,
	time_peak_m 	VARCHAR(2),
	time_end		TIMESTAMP,
	time_end_m	    VARCHAR(2),
	latitude		FLOAT,
    longitude		FLOAT,
	long_carr		FLOAT,
    optical_class	VARCHAR(10),
    PRIMARY KEY (kso_id)
);

DROP TABLE eit_list;
CREATE TABLE eit_list (
	eit_id			SERIAL,
	time_start		TIMESTAMP,
	previmg_time		TIMESTAMP,
	img_time		TIMESTAMP,
	quality			VARCHAR(4),
	latitude		FLOAT,
	longitude		FLOAT,
	speed_planeofsky		FLOAT,
    speed_proj        FLOAT, 
    pa_central        FLOAT,
	description	      VARCHAR(128),
	PRIMARY KEY (eit_id)
);

DROP TABLE yohkoh_sxt_trace_list;
CREATE TABLE yohkoh_sxt_trace_list (
	yst_id			SERIAL,
    link    VARCHAR(128),
	time_start_sxt		TIMESTAMP,
	time_end_sxt		TIMESTAMP,
	xray_class		VARCHAR(10),
	n_img		INTEGER,
	x_arcsec_sxt		FLOAT,
	y_arcsec_sxt		FLOAT,
	time_start		TIMESTAMP,
	time_end		TIMESTAMP,
	time_sxt_trace	TIMESTAMP,
    wl_dom  INTEGER,
    x_arcsec		FLOAT,
	y_arcsec		FLOAT,
	n171		INTEGER,
	n195		INTEGER,
	n284		INTEGER,
	n1600		INTEGER,
	n1216		INTEGER,
	nwl		INTEGER,
	PRIMARY KEY (yst_id)
);

DROP TABLE halpha_flares_event;
CREATE TABLE halpha_flares_event (
	ha_id			SERIAL,
	time_start		TIMESTAMP,
	time_peak		TIMESTAMP,
	time_end		TIMESTAMP,
    nar				INTEGER,
	latitude		FLOAT,
    longitude		FLOAT,
	long_carr		FLOAT,
	xray_class		VARCHAR(10),
    optical_class	VARCHAR(10),
    PRIMARY KEY (ha_id));

BEGIN;
DROP TABLE gle_list;
COMMIT;
CREATE TABLE gle_list (
        gle_id                  SERIAL,
        time_start              TIMESTAMP,
        time_end                TIMESTAMP,
        rem                     VARCHAR(64),
PRIMARY KEY (gle_id));

BEGIN;
DROP TABLE aastar_list;
COMMIT;
CREATE TABLE aastar_list (
        aastar_id               SERIAL,
        time_start              TIMESTAMP,
        time_peak               TIMESTAMP,
        time_end                TIMESTAMP,
        hduration               INTEGER,
        aastar_max              INTEGER,
        aastar_ave              INTEGER,
        aastar_sum              INTEGER,
PRIMARY KEY (aastar_id));

BEGIN;
DROP TABLE apstar_list;
COMMIT;
CREATE TABLE apstar_list (
        apstar_id               SERIAL,
        time_start              TIMESTAMP,
        time_peak               TIMESTAMP,
        time_end                TIMESTAMP,
        hduration               INTEGER,
        apstar_max              INTEGER,
        apstar_ave              INTEGER,
        apstar_sum              INTEGER,
PRIMARY KEY (apstar_id));

BEGIN;
DROP TABLE ssc_list;
COMMIT;
CREATE TABLE ssc_list (
        ssc_id               SERIAL,
        time_start           TIMESTAMP,
        nstn_A               INTEGER,
        nstn_B               INTEGER,
        nstn_C               INTEGER,
        nsi                  INTEGER,
PRIMARY KEY (ssc_id));

BEGIN;
DROP TABLE forbush_list;
COMMIT;
CREATE TABLE forbush_list (
        forbush_id               SERIAL,
        time_start           TIMESTAMP,
        sc                   VARCHAR(4),
        fe_magn FLOAT,
        kpmax FLOAT,
        dstmin FLOAT,
        bimf_m FLOAT,
        vsw_max FLOAT,
        axy_min FLOAT,
        az_r FLOAT,
        tmin FLOAT,
        dmin FLOAT,
        tdmn FLOAT,
        aftob FLOAT,
        tilt FLOAT,
PRIMARY KEY (forbush_id));

BEGIN;
DROP TABLE solar_wind_event;
COMMIT;
CREATE TABLE solar_wind_event (
        solar_wind_event_id               SERIAL,
        sw_list              INTEGER,
        time_start              TIMESTAMP,
        time_end                TIMESTAMP,
        bzn BOOLEAN,
        bzs BOOLEAN,
        eyc BOOLEAN,
        hss BOOLEAN,
        imc BOOLEAN,
        ir BOOLEAN,
        is_ BOOLEAN,
        lss BOOLEAN,
        pc BOOLEAN,
        sbc BOOLEAN,
        misc BOOLEAN,
        w 	BOOLEAN,
        i8 BOOLEAN,
        ace BOOLEAN,
        rem              VARCHAR(256),
PRIMARY KEY (solar_wind_event_id));

BEGIN;
DROP TABLE hi_cme_list;
COMMIT;
CREATE TABLE hi_cme_list (
        hi_cme_list_id         SERIAL,
        time_start              TIMESTAMP,
        time_end                TIMESTAMP,
        instrument              VARCHAR(8),
        data_gap INTEGER,
        cme_type VARCHAR(4),
        brightness VARCHAR(8),
     	pa_central		FLOAT,
	pa_width		FLOAT,
        time_onset TIMESTAMP,
        not_in_list INTEGER,
        linear_speed FLOAT,
        longitude FLOAT,
        longitude_err FLOAT,
        time_1au TIMESTAMP,
        description             VARCHAR(255),
PRIMARY KEY (hi_cme_list_id));

-- Creation of metadata tables
DROP TABLE sec_catalogue;
CREATE TABLE sec_catalogue (
	cat_id			INTEGER,
	name			VARCHAR(32),
	description		VARCHAR(128),
	type			VARCHAR(20),
	status			VARCHAR(20),
	url                     VARCHAR(128),
        hec_groups_id           INTEGER,
        bg_color                VARCHAR(15),
        longdescription         VARCHAR(256),
        timefrom   DATE,
        timeto     DATE,
        flare  VARCHAR(2),
        cme    VARCHAR(2),
        swind  VARCHAR(2),
        part   VARCHAR(2),
        otyp   VARCHAR(2),
        solar  VARCHAR(2),
        ips    VARCHAR(2),
        geo    VARCHAR(2),
        planet    VARCHAR(2),
        sort INTEGER,
	PRIMARY KEY (cat_id)	
);

DROP TABLE sec_attribute;
CREATE TABLE sec_attribute (
	attr_id			INTEGER,
	name			VARCHAR(30),
	description		VARCHAR(80),
	type			VARCHAR(20),
	PRIMARY KEY (attr_id)
);

DROP TABLE sec_cat_attr;
CREATE TABLE sec_cat_attr (
	cat_id			INTEGER,
	attr_id			INTEGER
);

BEGIN;
DROP TABLE hec_groups;
COMMIT;
CREATE TABLE hec_groups (
        hec_groups_id                  SERIAL,
        hec_group_name                 VARCHAR(128),
PRIMARY KEY (hec_groups_id));


-- Permission settings
REVOKE ALL ON TABLE
	sec_catalogue,
	sec_attribute,
	sec_cat_attr,
	noaa_proton_event,
	sgas_event,
	hessi_flare,
	lasco_cme_list,
	lasco_cme_cat,
	sidc_sunspot_number,
	drao_10cm_flux,
	dsd_list,
	yohkoh_flare_list,
	srs_list,
	bas_magnetic_storms,
	goes_xray_flare,
	soho_camp,
    kso_flare,
    eit_list,
    yohkoh_sxt_trace_list,
    halpha_flares_event,
    gle_list,
    aastar_list,
    apstar_list,
    ssc_list,
    forbush_list,
    solar_wind_event,
    hi_cme_list
FROM apache;

GRANT SELECT ON TABLE
	sec_catalogue,
	sec_attribute,
	sec_cat_attr,
	noaa_proton_event,
	sgas_event,
	hessi_flare,
	lasco_cme_list,
	lasco_cme_cat,
	sidc_sunspot_number,
	drao_10cm_flux,
	dsd_list,
	yohkoh_flare_list,
	srs_list,
	bas_magnetic_storms,
	goes_xray_flare,
	soho_camp,
    kso_flare,
    eit_list,
    yohkoh_sxt_trace_list,
    halpha_flares_event,
    gle_list,
    aastar_list,
    apstar_list,
    ssc_list,
    forbush_list,
    solar_wind_event,
    hi_cme_list,
goes_sxr_flare,
ngdc_halpha_flare,
noaa_energetic_event,
soho_lasco_cme,
stereo_hi_cme,
goes_proton_event,
aad_gle,
ngdc_aastar_storm,
ngdc_apstar_storm,
ngdc_ssc,
cme_forbush_event,
yohkoh_hxr_flare,
rhessi_hxr_flare,
kso_halpha_flare,
soho_eit_wave_transient,
stereo_hi_sw_transient,
wind_mfi_mag_cloud,
wind_mfi_bs_crossing_time,
istp_sw_event,
soho_pm_ip_shock,
wind_mfi_ip_shock,
wind_sw_crossing_time,
imp8_sw_crossing_time,
tsrs_solar_radio_event,
wind_typeii_soho_cme,
wind_stereo_ii_iv_radioburst,
ulysses_swoops_icme,
stereo_euvi_event,
noaa_active_region_summary,
noaa_daily_solar_data,
cactus_soho_cme,
halo_cme_flare_magnetic_storm,
ulysses_grb_xray_flare,
cactus_stereoa_cme,
cactus_stereob_cme,
cactus_soho_flow,
cactus_stereoa_flow,
cactus_stereob_flow,
cactus_all

TO apache;

REVOKE ALL ON TABLE
	sec_catalogue,
	sec_attribute,
	sec_cat_attr,
	noaa_proton_event,
	sgas_event,
	hessi_flare,
	lasco_cme_list,
	lasco_cme_cat,
	sidc_sunspot_number,
	drao_10cm_flux,
	dsd_list,
	yohkoh_flare_list,
	srs_list,
	bas_magnetic_storms,
	goes_xray_flare,
	soho_camp,
    kso_flare,
    eit_list,
    yohkoh_sxt_trace_list,
    halpha_flares_event,
    gle_list,
    aastar_list,
    apstar_list,
    ssc_list,
    forbush_list,
    solar_wind_event,
    hi_cme_list
FROM root;

GRANT ALL ON TABLE
	sec_catalogue,
	sec_attribute,
	sec_cat_attr,
	noaa_proton_event,
	sgas_event,
	hessi_flare,
	lasco_cme_list,
	lasco_cme_cat,
	sidc_sunspot_number,
	drao_10cm_flux,
	dsd_list,
	yohkoh_flare_list,
	srs_list,
	bas_magnetic_storms,
	goes_xray_flare,
	soho_camp,
    kso_flare,
    eit_list,
    yohkoh_sxt_trace_list,
    halpha_flares_event,
    gle_list,
    aastar_list,
    apstar_list,
    ssc_list,
    forbush_list,
    solar_wind_event,
    hi_cme_list
TO root;

	
