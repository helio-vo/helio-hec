-- EGSO - SEC server
-- # INAF - Trieste Astronomical Observatory
-- script for metadata fulfilling
-- by M.Jurcev,A.Santin last rev. 10-dec-2010


DELETE FROM sec_catalogue;
INSERT INTO sec_catalogue VALUES(1,'goes_xray_flare','GEV GOES event list','event','active','ftp://ftp.ngdc.noaa.gov/STP/SOLAR_DATA/SOLAR_FLARES/FLARES_XRAY',1,'ccaaaa','Edited list of GOES flares, locations from SXT, EIT, SXI (NGDC)');
INSERT INTO sec_catalogue VALUES(2,'halpha_flares_event','Solar H-alpha Flare events','event','active','http://www.ngdc.noaa.gov/stp/SOLAR/ftpsolarflares.html#halpha',1,'ccaaaa','List of H-alpha flares assembled from ground-based observatories (NGDC)');
INSERT INTO sec_catalogue VALUES(3,'sgas_event','NOAA SGAS Energetic Events','event','active','ftp://solar.sec.noaa.gov/pub/forecasts/SGAS',1,'ccbbbb','Energetic Solar Events, plus radio observations (NOAA)');
INSERT INTO sec_catalogue VALUES(4,'yohkoh_flare_list','Yohkoh HXT Flare List','event','closed','http://isass1.solar.isas.jaxa.jp/sxt_co/hxt_flare_list.txt',3,'ccbbbb','Flare list from the Yohkoh Hard X-ray Telescope');
INSERT INTO sec_catalogue VALUES(5,'hessi_flare','HESSI Flare List','event','active','http://hesperia.gsfc.nasa.gov/ssw/hessi/dbase',3,'ccbbbb','List of hard X-ray flares seen by RHESSI');
INSERT INTO sec_catalogue VALUES(6,'kso_flare','Kanzelhoehe Flare List','event','active','http://www.kso.ac.at',3,'ccbbbb','Flares observed in H-alpha by the Kanzelhoehe Observatory');
INSERT INTO sec_catalogue VALUES(7,'eit_list','EIT Waves','list','closed','http://umbra.nascom.nasa.gov/eit #[Contributed by Barbara Thompson (NASA/GSFC)]',3,'ccbbbb','Waves seen in the EUV by SOHO/EIT');
INSERT INTO sec_catalogue VALUES(8,'yohkoh_sxt_trace_list','Yohkoh SXT TRACE flare list','list','closed','http://www.lmsal.com/nitta/sxt_trace_flares/list.html',3,'ccbbbb','Common flares observed by Yohkoh/SXT and TRACE');
INSERT INTO sec_catalogue VALUES(9,'noaa_proton_event','NOAA Proton Events','event','active','http://umbra.nascom.nasa.gov/SEP/seps.html',2,'cccc99','Geo-effective Solar Energetic Proton (SEP) events (NOAA)');
INSERT INTO sec_catalogue VALUES(10,'lasco_cme_cat','SOHO/LASCO CME Catalogue','event','active','http://cdaw.gsfc.nasa.gov/CME_list/UNIVERSAL/text_ver/univ_all.txt',1,'cccc99','Edited list of CMEs detected by SOHO/LASCO (NASA/GSFC)');
INSERT INTO sec_catalogue VALUES(11,'lasco_cme_list','LASCO Preliminary CME List','event','active','ftp://lasco6.nascom.nasa.gov/pub/lasco/status',3,'cccc99','List of CMEs detected by SOHO/LASCO built from Alerts (SOHO/GSFC)');
INSERT INTO sec_catalogue VALUES(12,'bas_magnetic_storms','BAS Magnetic Storms','index','inactive','http://www.antarctica.ac.uk/SatelliteRisks #[Contributed by Richard Horne (BAS)]',2,'cccc99','Geo-effective magnetic storms, Dst < -50 nT (Contrib.: R. Horne, BAS)');
INSERT INTO sec_catalogue VALUES(13,'srs_list','NOAA SRS Active Regions','index','active','ftp://ftp.swpc.noaa.gov/pub/forecasts/SRS',4,'ivory','Parameters describing active regions: lat, long, size... (NOAA/USAF)');
INSERT INTO sec_catalogue VALUES(14,'soho_camp','SoHO Campaign','list','active','http://sohowww.nascom.nasa.gov/data/summary/asplanned/campaign/soho_campaign.dat',5,'ivory','List of observing campaigns planned for SOHO Mission (SOHO/GSFC)');
INSERT INTO sec_catalogue VALUES(15,'dsd_list','NOAA Daily Solar Data','index','active','http://www.sec.noaa.gov/ftpdir/indices/old_indices',4,'ivory','Parameters describing solar activity: SSN, flare types... (NOAA)');
INSERT INTO sec_catalogue VALUES(16,'sidc_sunspot_number','SIDC Smoothed Monthly Sunspot No.','index','active','http://sidc.oma.be/DATA/monthssn.dat',5,'ivory','Smoothed monthly sunspot number (SIDC, Belgium)');
INSERT INTO sec_catalogue VALUES(17,'drao_10cm_flux','DRAO 10.7cm Radio Flux Monitor','index','active','http://www.spaceweather.gc.ca/sx-eng.php',5,'ivory','General index of solar activity (DRAO)');
INSERT INTO sec_catalogue VALUES(18,'gle_list','Ground Level Enhancements','event','active','http://neutronm.bartol.udel.edu/~pyle/GLE_list.txt',2,'cccc99','Enhancements detected by ground-based neutron monitors (Bartol)');
INSERT INTO sec_catalogue VALUES(19,'aastar_list','Major Magnetic Storms (AA*)','event','active','http://www.ngdc.noaa.gov/stp/GEOMAG/aastar.shtml',2,'cccc99','Major Magnetic Storms according to the AA* criteria (NGDC)');
INSERT INTO sec_catalogue VALUES(20,'apstar_list','Major Magnetic Storms (Ap*)','event','active','http://www.ngdc.noaa.gov/stp/GEOMAG/apstar.shtml',2,'cccc99','Major Magnetic Storms according to the Ap* criteria (NGDC)');
INSERT INTO sec_catalogue VALUES(21,'ssc_list','Storm Sudden Commencements','event','active','http://www.ngdc.noaa.gov/stp/SOLAR/ftpSSC.html',2,'cccc99','Indicator of the onset of a Geo-magnetic storm (NGDC)');
INSERT INTO sec_catalogue VALUES(22,'forbush_list','Forbush Decrease Events','event','active','http://helios.izmiran.rssi.ru/',2,'cccc99','Forbush Decreases, related to CMEs (Contrib.: E. Eroshenko, Izmiran)');
INSERT INTO sec_catalogue VALUES(23,'solar_wind_event','Solar Wind Events','event','active','http://www-istp.gsfc.nasa.gov/scripts/sw-cat/grep-ls/sw-cat-categories.html',2,'cccc99','Solar Wind Catalog (ISTP)');
INSERT INTO sec_catalogue VALUES(24,'hi_cme_list','STEREO/HI CME list','event','inactive','http://www.sstd.rl.ac.uk/Stereo/Events%20Page.html',1,'ivory','STEREO/HI CME list');
INSERT INTO sec_catalogue VALUES(25,'hi_event','STEREO/HI solar wind transient events','event','inactive','http://www.sstd.rl.ac.uk/Stereo/HIEventList.html',3,'ivory','STEREO/HI solar wind transient events');
INSERT INTO sec_catalogue VALUES(26,'wind_imf_mag_cloud','WIND IMF Magnetic Clouds','event','inactive','http://wind.nasa.gov/mfi_instrument/mfi/mag_cloud_pub1.html#table',3,'ivory','WIND IMF MAGNETIC CLOUDS');
INSERT INTO sec_catalogue VALUES(27,'wind_imf_bow_shock','WIND IMF Bow Shock crossing','event','inactive','http://wind.nasa.gov/mfi_instrument/mfi/bow_shock.html',3,'ivory','WIND IMF Bow Shock crossing');
INSERT INTO sec_catalogue VALUES(28,'istp_solar_wind_cat','ISTP solar wind catalogue candidate events','event','inactive','http://www-spof.gsfc.nasa.gov/scripts/sw-cat/Catalog_events.html',3,'ivory','ISTP solar wind catalogue candidate events');
INSERT INTO sec_catalogue VALUES(29,'soho_pm_ips','SOHO PM IP Shocks','event','inactive','http://umtof.umd.edu/pm/FIGS.html',3,'ivory','An incomplete list of possible Interplanetary Shocks observed by the PM');
INSERT INTO sec_catalogue VALUES(30,'wind_imf_ips','WIND IMF IP Shocks','event','inactive','http://wind.nasa.gov/mfi_instrument/mfi/ip_shock.html',3,'ivory','WIND IMF IPS');
INSERT INTO sec_catalogue VALUES(31,'wind_solar_wind_times','WIND - times in the solar wind','event','inactive','http://www-spof.gsfc.nasa.gov/scripts/sw-cat/Catalog_SWtimes.html',3,'ivory','WIND SOLAR WIND TIMES');
INSERT INTO sec_catalogue VALUES(32,'imp8_solar_wind_times','IMP8 - times in the solar wind','event','inactive','http://www-spof.gsfc.nasa.gov/scripts/sw-cat/Catalog_SWtimes.html',3,'ivory','IMP8 SOLAR WIND TIMES');
INSERT INTO sec_catalogue VALUES(33,'tsrs_event_list','TSRS event list','event','inactive','http://radiosun.oats.inaf.it',3,'ivory','Trieste Solar Radio System event list');

INSERT INTO sec_catalogue VALUES(34,'latest_gev_flare','latest_gev_flare','event','active','http://www.mssl.ucl.ac.uk/~rdb/egso-sec/latest_gev.txt',3,'ivory','latest_gev_flare');
INSERT INTO sec_catalogue VALUES(35,'gevloc_flares','gevloc_flares','event','active','http://www.mssl.ucl.ac.uk/~rdb/soars/gevloc_flares.txt',3,'ivory','gevloc_flares');
INSERT INTO sec_catalogue VALUES(36,'wind_waves_type_ii_burst','WIND waves type II burst','event','inactive','http://cdaw.gsfc.nasa.gov/CME_list/radio/waves_type2.html',3,'ivory','Wind/WAVES type II bursts and CMEs');
INSERT INTO sec_catalogue VALUES(37,'waves_exp_wind_stereo','WAVES EXP WIND STEREO','event','inactive','http://lep694.gsfc.nasa.gov/waves/',3,'ivory','HEC metadata for WAVES Experiment on WIND and STEREO - Type II/IV List
');
INSERT INTO sec_catalogue VALUES(38,'ulysses_swoops_icme','Ulysses/SWOOPS CME list','event','inactive','http://swoops.lanl.gov/Ulysses-SWOOPSICMElist.doc',3,'ivory','Ulysses/SWOOPS CME list');
INSERT INTO sec_catalogue VALUES(39,'stereo_euvi_catalog','STEREO EUVI catalog','event','inactive','http://secchi.lmsal.com/EUVI/euvi_catalog.txt',3,'ivory','STEREO EUVI catalog');


DELETE FROM hec_groups;
INSERT INTO hec_groups VALUES (1,'Primary Solar Event Lists');
INSERT INTO hec_groups VALUES (2,'Geo-effective Events');
INSERT INTO hec_groups VALUES (3,'Solar Event Lists from individual observatories (flares, etc.)');
INSERT INTO hec_groups VALUES (4,'Summary Data');
INSERT INTO hec_groups VALUES (5,'Indices');


-- attribute description
-- types:
--		t = datetime
--		i = integer number
--		f = float number
--		b = boolean
--		s = string
DELETE FROM sec_attribute;
INSERT INTO sec_attribute VALUES(  1,'time_start',		'event start time',				't');
INSERT INTO sec_attribute VALUES(  2,'time_peak',		'event peak time',				't');
INSERT INTO sec_attribute VALUES(  3,'time_end',		'event end time',				't');
INSERT INTO sec_attribute VALUES(  4,'nar',				'active region number',			'i');
INSERT INTO sec_attribute VALUES(  5,'latitude',		'heliographic latitude',		'f');
INSERT INTO sec_attribute VALUES(  6,'longitude',		'heliographic longitude',		'f');
INSERT INTO sec_attribute VALUES(  7,'x_arcsec',		'x position [arcsec]'	,		'f');
INSERT INTO sec_attribute VALUES(  8,'y_arcsec',		'y position [arcsec]',			'f');
INSERT INTO sec_attribute VALUES(  9,'radial_arcsec',	'sun radius [arcsec]',			'f');
INSERT INTO sec_attribute VALUES( 10,'pa_central',		'polar angle [deg]',			'f');
INSERT INTO sec_attribute VALUES( 11,'pa_measure',		'polar angle measure [deg]',	'f');
INSERT INTO sec_attribute VALUES( 12,'proton_flux',		'proton flux units',			'f');
INSERT INTO sec_attribute VALUES( 13,'assoc_cme',		'associated CME',				's');
INSERT INTO sec_attribute VALUES( 14,'assoc_flare_pk',	'peak time for associated flare','t');
INSERT INTO sec_attribute VALUES( 15,'xray_class',		'x-ray importance class',		's');
INSERT INTO sec_attribute VALUES( 16,'optical_class',	'optical importance class',		's');
INSERT INTO sec_attribute VALUES( 17,'radio_245mhz',	'radio emission at 245 MHz',	'i');
INSERT INTO sec_attribute VALUES( 18,'radio_10cm',		'radio emission at 10 cm',		'i');
INSERT INTO sec_attribute VALUES( 19,'radio_sweep_ii',	'radio sweep type II',			'b');
INSERT INTO sec_attribute VALUES( 20,'radio_sweep_iv',	'radio sweep type IV',			'b');
INSERT INTO sec_attribute VALUES( 21,'swf',				'short wave fade',				's');
INSERT INTO sec_attribute VALUES( 22,'duration',		'duration [s]',					'i');
INSERT INTO sec_attribute VALUES( 23,'count_sec_peak',	'peak of counts per sec [s-1]',	'i');
INSERT INTO sec_attribute VALUES( 24,'total_count',		'total counts',					'i');
INSERT INTO sec_attribute VALUES( 25,'energy_kev',		'energy [keV]',					'f');
INSERT INTO sec_attribute VALUES( 26,'flare_number',	'flare id number',				'i');
INSERT INTO sec_attribute VALUES( 27,'description',		'event description',			's');
INSERT INTO sec_attribute VALUES( 28,'pa_width',		'polar angle width [deg]',		'f');
INSERT INTO sec_attribute VALUES( 29,'linear_speed',	'linear speed [km/sec]',		'f');
INSERT INTO sec_attribute VALUES( 30,'speed2_init',		'2nd order initial speed [km/sec]','f');
INSERT INTO sec_attribute VALUES( 31,'speed2_final',	'2nd order final speed [km/sec]','f');
INSERT INTO sec_attribute VALUES( 32,'speed2_20r',		'2nd order speed at 20 R [km/sec]','f');
INSERT INTO sec_attribute VALUES( 33,'acceleration',	'acceleration [km/sec^2]',		'f');
INSERT INTO sec_attribute VALUES( 34,'ssn',				'sunspot number',				'f');
INSERT INTO sec_attribute VALUES( 35,'sfu_observed',	'observed solar flux units',	'f');
INSERT INTO sec_attribute VALUES( 36,'sfu_adjusted',	'adjusted solar flux units',	'f');
INSERT INTO sec_attribute VALUES( 37,'sfu_series_d',	'series d solar flux units',	'f');
INSERT INTO sec_attribute VALUES( 38,'sesc_ssn',		'sesc sunspot number',			'i');
INSERT INTO sec_attribute VALUES( 39,'ssa',				'sunspot area',					'i');
INSERT INTO sec_attribute VALUES( 40,'new_regions',		'new regions',					'i');
INSERT INTO sec_attribute VALUES( 41,'stan_smf',		'stan smf',						'i');
INSERT INTO sec_attribute VALUES( 42,'xray_bkg',		'x-ray bkg',					's');
INSERT INTO sec_attribute VALUES( 43,'c_flares',		'class C flares',				'i');
INSERT INTO sec_attribute VALUES( 44,'m_flares',		'class M flares',				'i');
INSERT INTO sec_attribute VALUES( 45,'x_flares',		'class X flares',				'i');
INSERT INTO sec_attribute VALUES( 46,'opts_flares',		'opts flares',					'i');
INSERT INTO sec_attribute VALUES( 47,'opt1_flares',		'opt1 flares',					'i');
INSERT INTO sec_attribute VALUES( 48,'opt2_flares',		'opt2 flares',					'i');
INSERT INTO sec_attribute VALUES( 49,'opt3_flares',		'opt3 flares',					'i');
INSERT INTO sec_attribute VALUES( 50,'hxt_lo',			'HXT lo',						'i');
INSERT INTO sec_attribute VALUES( 51,'hxt_m1',			'HXT m1',						'i');
INSERT INTO sec_attribute VALUES( 52,'hxt_m2',			'HXT m2',						'i');
INSERT INTO sec_attribute VALUES( 53,'hxt_hi',			'HXT hi',						'i');
INSERT INTO sec_attribute VALUES( 54,'rem',				'Remarks',						's');
INSERT INTO sec_attribute VALUES( 55,'yoh_event',		'Yohkoh event',					'i');
INSERT INTO sec_attribute VALUES( 56,'long_carr',		'Carrington longitude',			'f');
INSERT INTO sec_attribute VALUES( 57,'area',			'area',							'f');
INSERT INTO sec_attribute VALUES( 58,'zurich_class',	'Zurich classification',		's');
INSERT INTO sec_attribute VALUES( 59,'p_value',			'p value',						's');
INSERT INTO sec_attribute VALUES( 60,'c_value',			'c value',						's');
INSERT INTO sec_attribute VALUES( 61,'long_extent',		'longitudinal extension',		'f');
INSERT INTO sec_attribute VALUES( 62,'n_spots',			'number of sunspots',			'i');
INSERT INTO sec_attribute VALUES( 63,'mag_class',		'mag class',					's');
INSERT INTO sec_attribute VALUES( 64,'region_type',		'active region type',			's');
INSERT INTO sec_attribute VALUES( 65,'dst',				'magnetic field [nT]',			'i');
INSERT INTO sec_attribute VALUES( 66,'hduration',		'duration [h]',					'i');
INSERT INTO sec_attribute VALUES( 67,'sohoc_num',		'SoHO Campaign number',			'i');
INSERT INTO sec_attribute VALUES( 68,'sohoc_name',		'SoHO Campaign name',			's');
INSERT INTO sec_attribute VALUES( 69,'sohoc_type',		'SoHO Campaign type',			's');
INSERT INTO sec_attribute VALUES( 70,'sohoc_obj',		'SoHO Campaign objective',		's');
INSERT INTO sec_attribute VALUES( 71,'sohoc_coord',		'SoHO Campaign coordinators',	's');
INSERT INTO sec_attribute VALUES( 72,'sohoc_part',		'SoHO Campaign participants',	's');
INSERT INTO sec_attribute VALUES( 73,'sohoc_comm',		'SoHO Campaign comments',		's');
INSERT INTO sec_attribute VALUES( 74,'ntime_start',		'selection start time',			't');
INSERT INTO sec_attribute VALUES( 75,'ntime_end',		'selection end time',			't');
INSERT INTO sec_attribute VALUES( 76,'time_start_m',	'event start time modifier',    's');
INSERT INTO sec_attribute VALUES( 77,'time_peak_m',	    'event peak modifier',          's');
INSERT INTO sec_attribute VALUES( 78,'time_end_m',  	'event end time modifier',      's');
INSERT INTO sec_attribute VALUES( 79,'previmg_time',  	'Previous Image Time',      't');
INSERT INTO sec_attribute VALUES( 80,'img_time',  	'Image Time',      't');
INSERT INTO sec_attribute VALUES( 81,'quality',  	'Quality Rating',      's');
INSERT INTO sec_attribute VALUES( 82,'speed_planeofsky',  	'Plane-of-Sky Speed',      'f');
INSERT INTO sec_attribute VALUES( 83,'speed_proj',  	'Projected Speed',      'f');
INSERT INTO sec_attribute VALUES( 84,'link',  	'The location of the SXT PFI file at ISAS',      's');
INSERT INTO sec_attribute VALUES( 85,'time_start_sxt',  'Times of the first flare mode SXT images',      't');
INSERT INTO sec_attribute VALUES( 86,'time_end_sxt',  	'Times of the last flare mode SXT images',      't');
INSERT INTO sec_attribute VALUES( 87,'n_img',  	'Number of flare mode SXT images',      'i');
INSERT INTO sec_attribute VALUES( 88,'x_arcsec_sxt',  	'Coord. X (arcsec from disk center) of the center of the 6th SXT flare mode image',      'f');
INSERT INTO sec_attribute VALUES( 89,'y_arcsec_sxt',  	'Coord. Y (arcsec from disk center) of the center of the 6th SXT flare mode image',      'f');
INSERT INTO sec_attribute VALUES( 90,'time_sxt_trace',  'Time of the TRACE image closest to time_start_sxt',      't');
INSERT INTO sec_attribute VALUES( 91,'wl_dom',  	    'Wavelength at which the greatest number of images is taken',      'i');
INSERT INTO sec_attribute VALUES( 92,'n171',  	'Number of TRACE images at 171 A',      'i');
INSERT INTO sec_attribute VALUES( 93,'n195',  	'Number of TRACE images at 195 A',      'i');
INSERT INTO sec_attribute VALUES( 94,'n284',  	'Number of TRACE images at 284 A',      'i');
INSERT INTO sec_attribute VALUES( 95,'n1600',  	'Number of TRACE images at 1600 A',      'i');
INSERT INTO sec_attribute VALUES( 96,'n1216',  	'Number of TRACE images at 1216 A',      'i');
INSERT INTO sec_attribute VALUES( 97,'nwl',  	'Number of TRACE images at WL',      'i');
--
INSERT INTO sec_attribute VALUES( 98,'aastar_max','max AA* magnetic index','i');
INSERT INTO sec_attribute VALUES( 99,'aastar_ave','ave AA* magnetic index','i');
INSERT INTO sec_attribute VALUES(100,'aastar_sum','sum AA* magnetic index','i');
--
INSERT INTO sec_attribute VALUES(101,'apstar_max','max Ap* magnetic index','i');
INSERT INTO sec_attribute VALUES(102,'apstar_ave','ave Ap* magnetic index','i');
INSERT INTO sec_attribute VALUES(103,'apstar_sum','sum Ap* magnetic index','i');
--
INSERT INTO sec_attribute VALUES(104,'nstn_A','Number stations reporting an A event','i');
INSERT INTO sec_attribute VALUES(105,'nstn_B','Number stations reporting an B event','i');
INSERT INTO sec_attribute VALUES(106,'nstn_C','Number stations reporting an C event','i');
INSERT INTO sec_attribute VALUES(107,'nsi','Number of stations reporting an "si" event','i');
--forbush
INSERT INTO sec_attribute VALUES(108,'sc','forbush sc','s');
INSERT INTO sec_attribute VALUES(109,'fe_magn','forbush fe_magn','f');
INSERT INTO sec_attribute VALUES(110,'kpmax','forbush kpmax','f');
INSERT INTO sec_attribute VALUES(111,'dstmin','forbush dstmin','f');
INSERT INTO sec_attribute VALUES(112,'bimf_m','forbush bimf_m','f');
INSERT INTO sec_attribute VALUES(113,'vsw_max','forbush vsw_max','f');
INSERT INTO sec_attribute VALUES(114,'axy_min','forbush axy_min','f');
INSERT INTO sec_attribute VALUES(115,'az_r','forbush az_r','f');
INSERT INTO sec_attribute VALUES(116,'tmin','forbush tmin','f');
INSERT INTO sec_attribute VALUES(117,'dmin','forbush dmin','f');
INSERT INTO sec_attribute VALUES(118,'tdmn','forbush tdmn','f');
INSERT INTO sec_attribute VALUES(119,'aftob','forbush aftob','f');
INSERT INTO sec_attribute VALUES(120,'tilt','forbush tilt','f');
--solar wind
INSERT INTO sec_attribute VALUES(121,'sw_list','solar wind list index','i');
INSERT INTO sec_attribute VALUES(122,'bzn','Interplanetary magnetic B-field North (GSM coordinate system)','b');
INSERT INTO sec_attribute VALUES(123,'bzs','Interplanetary magnetic B-field South (GSM coordinate system)','b');
INSERT INTO sec_attribute VALUES(124,'eyc','Change of the "y" component of the Interplanetary Electric Field','b');
INSERT INTO sec_attribute VALUES(125,'hss','High Speed Stream','b');
INSERT INTO sec_attribute VALUES(126,'imc','Interplanetary Magnetic Cloud','b');
INSERT INTO sec_attribute VALUES(127,'ir','Interaction Region','b');
INSERT INTO sec_attribute VALUES(128,'is_','Interplanetary Fast Shock','b');
INSERT INTO sec_attribute VALUES(129,'lss','Low Speed Stream','b');
INSERT INTO sec_attribute VALUES(130,'pc','Pressure Change','b');
INSERT INTO sec_attribute VALUES(131,'sbc ','Sector Boundaries Crossing, also heliospheric current sheet crossing','b');
INSERT INTO sec_attribute VALUES(132,'misc','Miscellaneous Events','b');
INSERT INTO sec_attribute VALUES(133,'w','WIND','b');
INSERT INTO sec_attribute VALUES(134,'i8','IMP-8','b');
INSERT INTO sec_attribute VALUES(135,'ace','ACE','b');


-- catalogue - attribute relationships
DELETE FROM sec_cat_attr;
-- goes:
INSERT INTO sec_cat_attr VALUES(1,	74	);
INSERT INTO sec_cat_attr VALUES(1,	1	);
INSERT INTO sec_cat_attr VALUES(1,	2	);
INSERT INTO sec_cat_attr VALUES(1,	3	);
INSERT INTO sec_cat_attr VALUES(1,	75	);
INSERT INTO sec_cat_attr VALUES(1,	4	);
INSERT INTO sec_cat_attr VALUES(1,	5	);
INSERT INTO sec_cat_attr VALUES(1,	6	);
INSERT INTO sec_cat_attr VALUES(1,	56	);
INSERT INTO sec_cat_attr VALUES(1,	15	);
INSERT INTO sec_cat_attr VALUES(1,	16	);
-- halpha:
INSERT INTO sec_cat_attr VALUES(2,	1	);
INSERT INTO sec_cat_attr VALUES(2,	2	);
INSERT INTO sec_cat_attr VALUES(2,	3	);
INSERT INTO sec_cat_attr VALUES(2,	4	);
INSERT INTO sec_cat_attr VALUES(2,	5	);
INSERT INTO sec_cat_attr VALUES(2,	6	);
INSERT INTO sec_cat_attr VALUES(2,	56	);
INSERT INTO sec_cat_attr VALUES(2,	15	);
INSERT INTO sec_cat_attr VALUES(2,	16	);
-- sgs:
INSERT INTO sec_cat_attr VALUES(3,	74	);
INSERT INTO sec_cat_attr VALUES(3,	1	);
INSERT INTO sec_cat_attr VALUES(3,	2	);
INSERT INTO sec_cat_attr VALUES(3,	3	);
INSERT INTO sec_cat_attr VALUES(3,	75	);
INSERT INTO sec_cat_attr VALUES(3,	4	);
INSERT INTO sec_cat_attr VALUES(3,	5	);
INSERT INTO sec_cat_attr VALUES(3,	6	);
INSERT INTO sec_cat_attr VALUES(3,	56	);
INSERT INTO sec_cat_attr VALUES(3,	15	);
INSERT INTO sec_cat_attr VALUES(3,	16	);
INSERT INTO sec_cat_attr VALUES(3,	17	);
INSERT INTO sec_cat_attr VALUES(3,	18	);
INSERT INTO sec_cat_attr VALUES(3,	19	);
INSERT INTO sec_cat_attr VALUES(3,	20	);
INSERT INTO sec_cat_attr VALUES(3,	21	);
-- yfc:
INSERT INTO sec_cat_attr VALUES(4,	1	);
INSERT INTO sec_cat_attr VALUES(4,	2	);
INSERT INTO sec_cat_attr VALUES(4,	3	);
INSERT INTO sec_cat_attr VALUES(4,	4	);
INSERT INTO sec_cat_attr VALUES(4,	5	);
INSERT INTO sec_cat_attr VALUES(4,	6	);
INSERT INTO sec_cat_attr VALUES(4,	15	);
INSERT INTO sec_cat_attr VALUES(4,	16	);
INSERT INTO sec_cat_attr VALUES(4,	50	);
INSERT INTO sec_cat_attr VALUES(4,	51	);
INSERT INTO sec_cat_attr VALUES(4,	52	);
INSERT INTO sec_cat_attr VALUES(4,	53	);
INSERT INTO sec_cat_attr VALUES(4,	54	);
INSERT INTO sec_cat_attr VALUES(4,	55	);
-- hef:
INSERT INTO sec_cat_attr VALUES(5,	1	);
INSERT INTO sec_cat_attr VALUES(5,	2	);
INSERT INTO sec_cat_attr VALUES(5,	3	);
INSERT INTO sec_cat_attr VALUES(5,	4	);
INSERT INTO sec_cat_attr VALUES(5,	7	);
INSERT INTO sec_cat_attr VALUES(5,	8	);
INSERT INTO sec_cat_attr VALUES(5,	9	);
INSERT INTO sec_cat_attr VALUES(5,	22	);
INSERT INTO sec_cat_attr VALUES(5,	23	);
INSERT INTO sec_cat_attr VALUES(5,	24	);
INSERT INTO sec_cat_attr VALUES(5,	25	);
INSERT INTO sec_cat_attr VALUES(5,	26	);
--kso:
INSERT INTO sec_cat_attr VALUES(6,	1	);
INSERT INTO sec_cat_attr VALUES(6,	76	);
INSERT INTO sec_cat_attr VALUES(6,	2	);
INSERT INTO sec_cat_attr VALUES(6,	77	);
INSERT INTO sec_cat_attr VALUES(6,	3	);
INSERT INTO sec_cat_attr VALUES(6,	78	);
INSERT INTO sec_cat_attr VALUES(6,	5	);
INSERT INTO sec_cat_attr VALUES(6,	6	);
INSERT INTO sec_cat_attr VALUES(6,	56	);
INSERT INTO sec_cat_attr VALUES(6,	16	);
-- eit:
INSERT INTO sec_cat_attr VALUES(7,	1	);
INSERT INTO sec_cat_attr VALUES(7,	79	);
INSERT INTO sec_cat_attr VALUES(7,	80	);
INSERT INTO sec_cat_attr VALUES(7,	81	);
INSERT INTO sec_cat_attr VALUES(7,	5	);
INSERT INTO sec_cat_attr VALUES(7,	6	);
INSERT INTO sec_cat_attr VALUES(7,	82	);
INSERT INTO sec_cat_attr VALUES(7,	83	);
INSERT INTO sec_cat_attr VALUES(7,	10	);
INSERT INTO sec_cat_attr VALUES(7,	27	);
-- yst:
INSERT INTO sec_cat_attr VALUES(8,	84	);
INSERT INTO sec_cat_attr VALUES(8,	85	);
INSERT INTO sec_cat_attr VALUES(8,	86	);
INSERT INTO sec_cat_attr VALUES(8,	15	);
INSERT INTO sec_cat_attr VALUES(8,	87	);
INSERT INTO sec_cat_attr VALUES(8,	88	);
INSERT INTO sec_cat_attr VALUES(8,	89	);
INSERT INTO sec_cat_attr VALUES(8,	1	);
INSERT INTO sec_cat_attr VALUES(8,	3	);
INSERT INTO sec_cat_attr VALUES(8,	90	);
INSERT INTO sec_cat_attr VALUES(8,	91	);
INSERT INTO sec_cat_attr VALUES(8,	7	);
INSERT INTO sec_cat_attr VALUES(8,	8	);
INSERT INTO sec_cat_attr VALUES(8,	92	);
INSERT INTO sec_cat_attr VALUES(8,	93	);
INSERT INTO sec_cat_attr VALUES(8,	94	);
INSERT INTO sec_cat_attr VALUES(8,	95	);
INSERT INTO sec_cat_attr VALUES(8,	96	);
INSERT INTO sec_cat_attr VALUES(8,	97	);
-- npe:
INSERT INTO sec_cat_attr VALUES(9,	1	);
INSERT INTO sec_cat_attr VALUES(9,	2	);
INSERT INTO sec_cat_attr VALUES(9,	4	);
INSERT INTO sec_cat_attr VALUES(9,	5	);
INSERT INTO sec_cat_attr VALUES(9,	6	);
INSERT INTO sec_cat_attr VALUES(9,	12	);
INSERT INTO sec_cat_attr VALUES(9,	13	);
INSERT INTO sec_cat_attr VALUES(9,	14	);
INSERT INTO sec_cat_attr VALUES(9,	15	);
INSERT INTO sec_cat_attr VALUES(9,	16	);
-- lcc:
INSERT INTO sec_cat_attr VALUES(10,	1	);
INSERT INTO sec_cat_attr VALUES(10,	3	);
INSERT INTO sec_cat_attr VALUES(10,	10	);
INSERT INTO sec_cat_attr VALUES(10,	11	);
INSERT INTO sec_cat_attr VALUES(10,	28	);
INSERT INTO sec_cat_attr VALUES(10,	29	);
INSERT INTO sec_cat_attr VALUES(10,	30	);
INSERT INTO sec_cat_attr VALUES(10,	31	);
INSERT INTO sec_cat_attr VALUES(10,	32	);
INSERT INTO sec_cat_attr VALUES(10,	33	);
-- lcl:
INSERT INTO sec_cat_attr VALUES(11,	1	);
INSERT INTO sec_cat_attr VALUES(11,	10	);
INSERT INTO sec_cat_attr VALUES(11,	27	);
-- bms:
INSERT INTO sec_cat_attr VALUES(12,	1	);
INSERT INTO sec_cat_attr VALUES(12,	2	);
INSERT INTO sec_cat_attr VALUES(12,	3	);
INSERT INTO sec_cat_attr VALUES(12,	65	);
INSERT INTO sec_cat_attr VALUES(12,	66	);
-- srs:
INSERT INTO sec_cat_attr VALUES(13,	1	);
INSERT INTO sec_cat_attr VALUES(13,	4	);
INSERT INTO sec_cat_attr VALUES(13,	5	);
INSERT INTO sec_cat_attr VALUES(13,	6	);
INSERT INTO sec_cat_attr VALUES(13,	56	);
INSERT INTO sec_cat_attr VALUES(13,	57	);
INSERT INTO sec_cat_attr VALUES(13,	58	);
INSERT INTO sec_cat_attr VALUES(13,	59	);
INSERT INTO sec_cat_attr VALUES(13,	60	);
INSERT INTO sec_cat_attr VALUES(13,	61	);
INSERT INTO sec_cat_attr VALUES(13,	62	);
INSERT INTO sec_cat_attr VALUES(13,	63	);
INSERT INTO sec_cat_attr VALUES(13,	64	);
--soho:
INSERT INTO sec_cat_attr VALUES(14,	1	);
INSERT INTO sec_cat_attr VALUES(14,	3	);
INSERT INTO sec_cat_attr VALUES(14,	67	);
INSERT INTO sec_cat_attr VALUES(14,	68	);
INSERT INTO sec_cat_attr VALUES(14,	69	);
INSERT INTO sec_cat_attr VALUES(14,	70	);
INSERT INTO sec_cat_attr VALUES(14,	71	);
INSERT INTO sec_cat_attr VALUES(14,	72	);
INSERT INTO sec_cat_attr VALUES(14,	73	);
-- dsd:
INSERT INTO sec_cat_attr VALUES(15,	1	);
INSERT INTO sec_cat_attr VALUES(15,	18	);
INSERT INTO sec_cat_attr VALUES(15,	38	);
INSERT INTO sec_cat_attr VALUES(15,	39	);
INSERT INTO sec_cat_attr VALUES(15,	40	);
INSERT INTO sec_cat_attr VALUES(15,	41	);
INSERT INTO sec_cat_attr VALUES(15,	42	);
INSERT INTO sec_cat_attr VALUES(15,	43	);
INSERT INTO sec_cat_attr VALUES(15,	44	);
INSERT INTO sec_cat_attr VALUES(15,	45	);
INSERT INTO sec_cat_attr VALUES(15,	46	);
INSERT INTO sec_cat_attr VALUES(15,	47	);
INSERT INTO sec_cat_attr VALUES(15,	48	);
INSERT INTO sec_cat_attr VALUES(15,	49	);
-- ssn:
INSERT INTO sec_cat_attr VALUES(16,	1	);
INSERT INTO sec_cat_attr VALUES(16,	3	);
INSERT INTO sec_cat_attr VALUES(16,	34	);
-- sfm:
INSERT INTO sec_cat_attr VALUES(17,	1	);
INSERT INTO sec_cat_attr VALUES(17,	3	);
INSERT INTO sec_cat_attr VALUES(17,	35	);
INSERT INTO sec_cat_attr VALUES(17,	36	);
INSERT INTO sec_cat_attr VALUES(17,	37	);
-- gle:
INSERT INTO sec_cat_attr VALUES(18,     1       );
INSERT INTO sec_cat_attr VALUES(18,     3       );
INSERT INTO sec_cat_attr VALUES(18,     54       );
-- aastar:
INSERT INTO sec_cat_attr VALUES(19,     1       );
INSERT INTO sec_cat_attr VALUES(19,     2       );
INSERT INTO sec_cat_attr VALUES(19,     3       );
INSERT INTO sec_cat_attr VALUES(19,     66       );
INSERT INTO sec_cat_attr VALUES(19,     98       );
INSERT INTO sec_cat_attr VALUES(19,     99       );
INSERT INTO sec_cat_attr VALUES(19,     100       );
-- apstar:
INSERT INTO sec_cat_attr VALUES(20,     1       );
INSERT INTO sec_cat_attr VALUES(20,     2       );
INSERT INTO sec_cat_attr VALUES(20,     3       );
INSERT INTO sec_cat_attr VALUES(20,     66       );
INSERT INTO sec_cat_attr VALUES(20,     101       );
INSERT INTO sec_cat_attr VALUES(20,     102       );
INSERT INTO sec_cat_attr VALUES(20,     103       );
-- ssc:
INSERT INTO sec_cat_attr VALUES(21,     1       );
INSERT INTO sec_cat_attr VALUES(21,     104       );
INSERT INTO sec_cat_attr VALUES(21,     105       );
INSERT INTO sec_cat_attr VALUES(21,     106       );
INSERT INTO sec_cat_attr VALUES(21,     107       );
-- forbush:
INSERT INTO sec_cat_attr VALUES(22,     1       );
INSERT INTO sec_cat_attr VALUES(22,     108     );
INSERT INTO sec_cat_attr VALUES(22,     109     );
INSERT INTO sec_cat_attr VALUES(22,     110     );
INSERT INTO sec_cat_attr VALUES(22,     111     );
INSERT INTO sec_cat_attr VALUES(22,     112     );
INSERT INTO sec_cat_attr VALUES(22,     113     );
INSERT INTO sec_cat_attr VALUES(22,     114     );
INSERT INTO sec_cat_attr VALUES(22,     115     );
INSERT INTO sec_cat_attr VALUES(22,     116     );
INSERT INTO sec_cat_attr VALUES(22,     117     );
INSERT INTO sec_cat_attr VALUES(22,     118     );
INSERT INTO sec_cat_attr VALUES(22,     119     );
INSERT INTO sec_cat_attr VALUES(22,     120     );
-- solar_wind_event:
INSERT INTO sec_cat_attr VALUES(23,     1       );
INSERT INTO sec_cat_attr VALUES(23,     3       );
INSERT INTO sec_cat_attr VALUES(23,     121     );
INSERT INTO sec_cat_attr VALUES(23,     122     );
INSERT INTO sec_cat_attr VALUES(23,     123     );
INSERT INTO sec_cat_attr VALUES(23,     124     );
INSERT INTO sec_cat_attr VALUES(23,     125     );
INSERT INTO sec_cat_attr VALUES(23,     126     );
INSERT INTO sec_cat_attr VALUES(23,     127     );
INSERT INTO sec_cat_attr VALUES(23,     128     );
INSERT INTO sec_cat_attr VALUES(23,     129     );
INSERT INTO sec_cat_attr VALUES(23,     130     );
INSERT INTO sec_cat_attr VALUES(23,     131     );
INSERT INTO sec_cat_attr VALUES(23,     132     );
INSERT INTO sec_cat_attr VALUES(23,     133     );
INSERT INTO sec_cat_attr VALUES(23,     134     );
INSERT INTO sec_cat_attr VALUES(23,     135     );
INSERT INTO sec_cat_attr VALUES(23,     54     );

-- hi_cme_list:
INSERT INTO sec_attribute VALUES(136,'instrument','instrument','s');
INSERT INTO sec_attribute VALUES(137,'data_gap','data gap','i');
INSERT INTO sec_attribute VALUES(138,'cme_type','CME type','s');
INSERT INTO sec_attribute VALUES(139,'time_onset','onset time','t');
INSERT INTO sec_attribute VALUES(140,'not_in_list','not in list flag','i');
INSERT INTO sec_attribute VALUES(141,'longitude_err','longitude error','f');
INSERT INTO sec_attribute VALUES(142,'time_1au','1AU time','t');

INSERT INTO sec_cat_attr VALUES(24,     1       );
INSERT INTO sec_cat_attr VALUES(24,     3       ); 
INSERT INTO sec_cat_attr VALUES(24,     136       ); --instrument
INSERT INTO sec_cat_attr VALUES(24,     137       ); --data_gap
INSERT INTO sec_cat_attr VALUES(24,     138       ); --cme_type
INSERT INTO sec_cat_attr VALUES(24,     15      ); --brightness rename to xray_class 15
INSERT INTO sec_cat_attr VALUES(24,     10      ); --pa_central
INSERT INTO sec_cat_attr VALUES(24,     28      ); --pa_width
INSERT INTO sec_cat_attr VALUES(24,     139       ); --time_onset rename to time_peak 2
INSERT INTO sec_cat_attr VALUES(24,     140       ); --not_in_list
INSERT INTO sec_cat_attr VALUES(24,     29      ); --linear_speed
INSERT INTO sec_cat_attr VALUES(24,     29      ); --longitude
INSERT INTO sec_cat_attr VALUES(24,     141       ); --longitude_err
INSERT INTO sec_cat_attr VALUES(24,     142       ); --time_1au
INSERT INTO sec_cat_attr VALUES(24,     27      ); --description 27 or rename to rem 54

-- hi_event:
INSERT INTO sec_attribute VALUES(143,'speed','speed','f');
INSERT INTO sec_attribute VALUES(144,'speed_err','speed error','f');

INSERT INTO sec_cat_attr VALUES(25,     1       );
INSERT INTO sec_cat_attr VALUES(25,     136       ); --instrument 136
INSERT INTO sec_cat_attr VALUES(25,     143       ); --speed, linear_speed???
INSERT INTO sec_cat_attr VALUES(25,     144       ); --speed_err
INSERT INTO sec_cat_attr VALUES(25,     29      ); --longitude
INSERT INTO sec_cat_attr VALUES(25,     141       ); --longitude_err 141
INSERT INTO sec_cat_attr VALUES(25,     27      ); --time_1au 27

-- wind_imf_mag_cloud:
INSERT INTO sec_attribute VALUES(145,'doy_start','start Day Of Year','i');
INSERT INTO sec_attribute VALUES(146,'doy_end','end Day Of Year','i');
INSERT INTO sec_attribute VALUES(147,'code','code','s');
INSERT INTO sec_attribute VALUES(148,'quality','quality','i');

INSERT INTO sec_cat_attr VALUES(26,     1       );
INSERT INTO sec_cat_attr VALUES(26,     3       );
INSERT INTO sec_cat_attr VALUES(26,     145       ); --doy_start
INSERT INTO sec_cat_attr VALUES(26,     146       ); --doy_end
INSERT INTO sec_cat_attr VALUES(26,     147       ); --code
INSERT INTO sec_cat_attr VALUES(26,     148       ); --quality

-- wind_imf_bow_shock:
INSERT INTO sec_attribute VALUES(149,'last_bs_crossing','last bs crossing','t');
INSERT INTO sec_attribute VALUES(150,'last_bs_flag','last bs flag','s');
INSERT INTO sec_attribute VALUES(151,'first_hr_kept','first hr kept','t');
INSERT INTO sec_attribute VALUES(152,'first_hr_flag','first hr flag','s');
INSERT INTO sec_attribute VALUES(153,'last_hr_kept','last hr kept','t');
INSERT INTO sec_attribute VALUES(154,'last_hr_flag','last hr flag','s');
INSERT INTO sec_attribute VALUES(155,'first_bs_crossing','first bs crossing','t');
INSERT INTO sec_attribute VALUES(156,'first_bs_crossing','first bs crossing','s');

INSERT INTO sec_cat_attr VALUES(27,     1       );
INSERT INTO sec_cat_attr VALUES(27,     3       );
INSERT INTO sec_cat_attr VALUES(27,     149       ); --last_bs_crossing
INSERT INTO sec_cat_attr VALUES(27,     150       ); --last_bs_flag
INSERT INTO sec_cat_attr VALUES(27,     151       ); --first_hr_kept
INSERT INTO sec_cat_attr VALUES(27,     152       ); --first_hr_flag
INSERT INTO sec_cat_attr VALUES(27,     153       ); --last_hr_kept
INSERT INTO sec_cat_attr VALUES(27,     154       ); --last_hr_flag
INSERT INTO sec_cat_attr VALUES(27,     155       ); --first_bs_crossing
INSERT INTO sec_cat_attr VALUES(27,     156       ); --first_bs_flag

-- istp_solar_wind_cat:
INSERT INTO sec_attribute VALUES(157,'category','category','s');
INSERT INTO sec_attribute VALUES(158,'spacecraft','spacecraft','s');

INSERT INTO sec_cat_attr VALUES(28,     1       );
INSERT INTO sec_cat_attr VALUES(28,     3       );
INSERT INTO sec_cat_attr VALUES(28,     157      ); --category, select category from istp_solar_wind_cat group by category;, category of solar events???
INSERT INTO sec_cat_attr VALUES(28,     158      ); --spacecraft, select spacecraft from istp_solar_wind_cat group by spacecraft;
INSERT INTO sec_cat_attr VALUES(28,     54      ); --rem

-- soho_pm_ips:
INSERT INTO sec_attribute VALUES(159,'zone','zone','s');

INSERT INTO sec_cat_attr VALUES(29,     1       );
INSERT INTO sec_cat_attr VALUES(29,     145       ); --doy_start, start Day Of Year
INSERT INTO sec_cat_attr VALUES(29,     159       ); --zone, zone
INSERT INTO sec_cat_attr VALUES(29,     54      );--rem

-- wind_imf_ips:
INSERT INTO sec_cat_attr VALUES(30,     1       );
-- wind_solar_wind_times:
INSERT INTO sec_cat_attr VALUES(31,     1       );
INSERT INTO sec_cat_attr VALUES(31,     3       );
-- imp8_solar_wind_times:
INSERT INTO sec_cat_attr VALUES(32,     1       );
INSERT INTO sec_cat_attr VALUES(32,     3       );
-- tsrs_event_list:
INSERT INTO sec_attribute VALUES(160,'freq','frequency','i');
INSERT INTO sec_attribute VALUES(161,'cpp','circular polarization percentage','i');
INSERT INTO sec_attribute VALUES(162,'ps','polarization sense','s');
INSERT INTO sec_attribute VALUES(163,'sfu_max','polarization sense','f');
INSERT INTO sec_attribute VALUES(164,'radio_class','radio solar burst profiles','s');
INSERT INTO sec_attribute VALUES(165,'file','image filename','s');

INSERT INTO sec_cat_attr VALUES(33,     1       );
INSERT INTO sec_cat_attr VALUES(33,     2       );
INSERT INTO sec_cat_attr VALUES(33,     3       );
INSERT INTO sec_cat_attr VALUES(33,     160      ); --freq, frequency MHz
INSERT INTO sec_cat_attr VALUES(33,     161      ); --cpp, circular polarization percentage 
INSERT INTO sec_cat_attr VALUES(33,     162      ); --ps, polarization sense
INSERT INTO sec_cat_attr VALUES(33,     163      ); --sfu_max, event solar flux maximum
INSERT INTO sec_cat_attr VALUES(33,     164      ); --radio_class, radio solar burst profiles
INSERT INTO sec_cat_attr VALUES(33,     54      ); --rem
INSERT INTO sec_cat_attr VALUES(33,     165      ); --file


-- latest_gev_flare:
INSERT INTO sec_cat_attr VALUES(34,     1       );
INSERT INTO sec_cat_attr VALUES(34,     2       );
INSERT INTO sec_cat_attr VALUES(34,     3       );
INSERT INTO sec_cat_attr VALUES(34,      4       );
INSERT INTO sec_cat_attr VALUES(34,      5       );
INSERT INTO sec_cat_attr VALUES(34,      6       );
INSERT INTO sec_cat_attr VALUES(34,      15      );
INSERT INTO sec_cat_attr VALUES(34,      16      );

-- gevloc_flares:
INSERT INTO sec_attribute VALUES(166,'loc','flare location','s');

INSERT INTO sec_cat_attr VALUES(35,     1       );
INSERT INTO sec_cat_attr VALUES(35,     15       );
INSERT INTO sec_cat_attr VALUES(35,     166       ); --loc, location

-- wind_waves_type_ii_burst:
INSERT INTO sec_attribute VALUES(167,'freq_start','start frequency','i');
INSERT INTO sec_attribute VALUES(168,'freq_end','end frequency','i');
INSERT INTO sec_attribute VALUES(169,'time_cme','CME time','t');

INSERT INTO sec_cat_attr VALUES(36,     1       );
INSERT INTO sec_cat_attr VALUES(36,     3       );
INSERT INTO sec_cat_attr VALUES(36,     167       ); --freq_start, start frequency
INSERT INTO sec_cat_attr VALUES(36,     168       ); --freq_end, end frequency
INSERT INTO sec_cat_attr VALUES(36,     166       ); --loc, location 166
INSERT INTO sec_cat_attr VALUES(36,     4       ); --nar
INSERT INTO sec_cat_attr VALUES(36,     15      ); --xray_class
INSERT INTO sec_cat_attr VALUES(36,     169       ); --time_cme, CME time
INSERT INTO sec_cat_attr VALUES(36,     10      ); --pa_central
INSERT INTO sec_cat_attr VALUES(36,     28      ); --pa_width
INSERT INTO sec_cat_attr VALUES(36,     143       ); --speed 143
INSERT INTO sec_cat_attr VALUES(36,     54      );--rem

-- waves_exp_wind_stereo:
INSERT INTO sec_attribute VALUES(170,'dyn_spec','dyn spec','s');

INSERT INTO sec_cat_attr VALUES(37,     1       );
INSERT INTO sec_cat_attr VALUES(37,     3       );
INSERT INTO sec_cat_attr VALUES(37,     167       ); --freq_start, start frequency 167
INSERT INTO sec_cat_attr VALUES(37,     168       ); --freq_end, end frequency 168
INSERT INTO sec_cat_attr VALUES(37,     170       ); --dyn_spec, dyn_spec
INSERT INTO sec_cat_attr VALUES(37,     54      );--rem

-- ulysses_swoops_icme:
INSERT INTO sec_attribute VALUES(171,'r','r','f');
INSERT INTO sec_attribute VALUES(172,'v_p','v p','f');
INSERT INTO sec_attribute VALUES(173,'v_p_err','v p error','f');
INSERT INTO sec_attribute VALUES(174,'b','b','f');
INSERT INTO sec_attribute VALUES(175,'b_err','b error','f');

INSERT INTO sec_cat_attr VALUES(38,     1       );
INSERT INTO sec_cat_attr VALUES(38,     3       );
INSERT INTO sec_cat_attr VALUES(38,     5       );
INSERT INTO sec_cat_attr VALUES(38,     6       );
INSERT INTO sec_cat_attr VALUES(38,     171       ); --r
INSERT INTO sec_cat_attr VALUES(38,     172       ); --v_p
INSERT INTO sec_cat_attr VALUES(38,     173       ); --v_p_err
INSERT INTO sec_cat_attr VALUES(38,     174       ); --b
INSERT INTO sec_cat_attr VALUES(38,     175       ); --b_err

-- stereo_euvi_catalog:
INSERT INTO sec_attribute VALUES(176,'cadence','cadence','i');
INSERT INTO sec_attribute VALUES(177,'rhessi_peak_range','rhessi peak range','s');
INSERT INTO sec_attribute VALUES(178,'rhessi_peak_count','rhessi peak count','i');
INSERT INTO sec_attribute VALUES(179,'cme_event_source','CME event source','s');

INSERT INTO sec_cat_attr VALUES(39,     1       );
INSERT INTO sec_cat_attr VALUES(39,     3       );
INSERT INTO sec_cat_attr VALUES(39,     166       ); --loc 166
INSERT INTO sec_cat_attr VALUES(39,     176       ); --cadence
INSERT INTO sec_cat_attr VALUES(39,     158      ); --spacecraft 158
INSERT INTO sec_cat_attr VALUES(39,     15       ); --xray_class
INSERT INTO sec_cat_attr VALUES(39,     177       ); --rhessi_peak_range
INSERT INTO sec_cat_attr VALUES(39,     178       ); --rhessi_peak_count
INSERT INTO sec_cat_attr VALUES(39,     54      );--rem
INSERT INTO sec_cat_attr VALUES(39,     179       ); --cme_event_source

