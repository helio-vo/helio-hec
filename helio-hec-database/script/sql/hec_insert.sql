-- EGSO - SFEC server
-- creation script
-- by M.Jurcev,A.Santin last rev. 27-jan-2007

BEGIN;
DELETE FROM sgas_event;
--COPY sgas_event (time_start,time_peak,time_end,nar,latitude,longitude,xray_class,optical_class,radio_245mhz,radio_10cm,radio_sweep_ii,radio_sweep_iv,swf) FROM '/var/www//sec/solar.info/SEL/SGAS.postgres.converted';
SELECT SETVAL((SELECT pg_get_serial_sequence('sgas_event','sgs_id')), 1, false);
COPY sgas_event (time_start,time_peak,time_end,nar,latitude,longitude,xray_class,optical_class,radio_245mhz,radio_10cm,radio_sweep_ii,radio_sweep_iv,swf,ntime_start,ntime_end,long_carr) FROM '/var/www//hec/temp/SGS.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM lasco_cme_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('lasco_cme_list','lcl_id')), 1, false);
COPY lasco_cme_list (time_start,pa_central,description) FROM '/var/www//hec/temp/LCL.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM lasco_cme_cat;
SELECT SETVAL((SELECT pg_get_serial_sequence('lasco_cme_cat','lcc_id')), 1, false);
COPY lasco_cme_cat (time_start,time_end,pa_central,pa_width,linear_speed,speed2_init,speed2_final,speed2_20r,acceleration,pa_measure) FROM '/var/www//hec/temp/LCC.postgres.converted';
COMMIT;
	
BEGIN;
DELETE FROM hessi_flare;
SELECT SETVAL((SELECT pg_get_serial_sequence('hessi_flare','hef_id')), 1, false);
COPY hessi_flare (flare_number,time_start,time_peak,time_end,duration,count_sec_peak,total_count,energy_kev,x_arcsec,y_arcsec,radial_arcsec,nar) FROM '/var/www//hec/temp/HEF.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM noaa_proton_event;
SELECT SETVAL((SELECT pg_get_serial_sequence('noaa_proton_event','npe_id')), 1, false);
COPY noaa_proton_event (time_start,time_peak,nar,latitude,longitude,proton_flux,assoc_cme,assoc_flare_pk,xray_class,optical_class) FROM '/var/www//hec/temp/NPE.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM sidc_sunspot_number;
SELECT SETVAL((SELECT pg_get_serial_sequence('sidc_sunspot_number','ssn_id')), 1, false);
COPY sidc_sunspot_number (time_start,time_end,ssn) FROM '/var/www//hec/temp/SSN.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM drao_10cm_flux;
SELECT SETVAL((SELECT pg_get_serial_sequence('drao_10cm_flux','sfm_id')), 1, false);
COPY drao_10cm_flux (time_start,time_end,sfu_observed,sfu_adjusted,sfu_series_d) FROM '/var/www//hec/temp/SFM.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM dsd_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('dsd_list','dsd_id')), 1, false);
COPY dsd_list (time_start,radio_10cm,sesc_ssn,ss_area,new_regions,stan_smf,xray_bkg,c_flares,m_flares,x_flares,opts_flares,opt1_flares,opt2_flares,opt3_flares) FROM '/var/www//hec/temp/DSD.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM yohkoh_flare_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('yohkoh_flare_list','yfc_id')), 1, false);
COPY yohkoh_flare_list (time_start,time_peak,time_end,nar,latitude,longitude,xray_class,optical_class,hxt_lo,hxt_m1,hxt_m2,hxt_hi,rem,yoh_event) FROM '/var/www//hec/temp/YFC.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM srs_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('srs_list','srs_id')), 1, false);
COPY srs_list (time_start,nar,latitude,longitude,long_carr,area,zurich_class,p_value,c_value,long_extent,n_spots,mag_class,region_type) FROM '/var/www//hec/temp/SRS.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM bas_magnetic_storms;
SELECT SETVAL((SELECT pg_get_serial_sequence('bas_magnetic_storms','bms_id')), 1, false);
COPY bas_magnetic_storms (time_start,time_peak,time_end,dst,hduration) FROM '/var/www//hec/temp/BMS.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM goes_xray_flare;
SELECT SETVAL((SELECT pg_get_serial_sequence('goes_xray_flare','goes_id')), 1, false);
COPY goes_xray_flare (time_start,time_peak,time_end,latitude,longitude,optical_class,xray_class,nar,ntime_start,ntime_end,long_carr) FROM '/var/www//hec/temp/GOES.postgres.converted';
COPY goes_xray_flare (time_start,time_peak,time_end,latitude,longitude,optical_class,xray_class,nar,ntime_start,ntime_end,long_carr) FROM '/var/www//hec/temp/latest_gev5.csv';
COMMIT;

BEGIN;
DELETE FROM soho_camp;
SELECT SETVAL((SELECT pg_get_serial_sequence('soho_camp','soho_id')), 1, false);
COPY soho_camp (sohoc_num,sohoc_name,sohoc_type,sohoc_obj,sohoc_coord,sohoc_part,sohoc_comm,time_start,time_end) FROM '/var/www//hec/temp/SOHO.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM kso_flare;
SELECT SETVAL((SELECT pg_get_serial_sequence('kso_flare','kso_id')), 1, false);
COPY kso_flare (time_start,time_start_m,time_peak,time_peak_m,time_end,time_end_m,latitude,longitude,long_carr,optical_class) FROM '/var/www//hec/temp/KSO.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM eit_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('eit_list','eit_id')), 1, false);
COPY eit_list (previmg_time, img_time, quality,latitude,longitude,speed_planeofsky, speed_proj, pa_central, description,time_start) FROM '/var/www//hec/temp/EIT.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM yohkoh_sxt_trace_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('yohkoh_sxt_trace_list','yst_id')), 1, false);
COPY yohkoh_sxt_trace_list (link,time_start_sxt,time_end_sxt,xray_class,n_img,x_arcsec_sxt,y_arcsec_sxt,time_start,time_end,time_sxt_trace,wl_dom,x_arcsec,y_arcsec,n171,n195,n284,n1600,n1216,nwl) FROM '/var/www//hec/temp/YST.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM halpha_flares_event;
SELECT SETVAL((SELECT pg_get_serial_sequence('halpha_flares_event','ha_id')), 1, false);
COPY halpha_flares_event (time_start,time_peak,time_end,latitude,longitude,optical_class,xray_class,nar,long_carr) FROM '/var/www//hec/temp/HA.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM gle_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('gle_list','gle_id')), 1, false);
COPY gle_list (time_start,time_end,rem) FROM '/var/www//hec/temp/GLE.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM aastar_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('aastar_list','aastar_id')), 1, false);
COPY aastar_list (time_start,time_peak,time_end,hduration,aastar_max,aastar_ave,aastar_sum) FROM '/var/www//hec/temp/AASTAR.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM apstar_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('apstar_list','apstar_id')), 1, false);
COPY apstar_list (time_start,time_peak,time_end,hduration,apstar_max,apstar_ave,apstar_sum) FROM '/var/www//hec/temp/APSTAR.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM ssc_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('ssc_list','ssc_id')), 1, false);
COPY ssc_list (time_start,nstn_A,nstn_B,nstn_C,nsi) FROM '/var/www//hec/temp/SSC.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM forbush_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('forbush_list','forbush_id')), 1, false);
COPY forbush_list (time_start,sc,fe_magn,kpmax,dstmin,bimf_m,vsw_max,axy_min,az_r,tmin,dmin,tdmn,aftob,tilt) FROM '/var/www//hec/temp/FORBUSH.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM solar_wind_event;
SELECT SETVAL((SELECT pg_get_serial_sequence('solar_wind_event','solar_wind_event_id')), 1, false);
COPY solar_wind_event (sw_list,time_start,time_end,bzn,bzs,eyc,hss,imc,ir,is_,lss,pc,sbc,misc,w,i8,ace,rem) FROM '/var/www//hec/temp/SW.postgres.converted';
COMMIT;

BEGIN;
DELETE FROM hi_cme_list;
SELECT SETVAL((SELECT pg_get_serial_sequence('hi_cme_list','hi_cme_list_id')), 1, false);
COPY hi_cme_list (time_start,instrument,time_end,data_gap,cme_type,brightness,pa_central,pa_width,time_onset,not_in_list,linear_speed,longitude,longitude_err,time_1au,description) FROM '/var/www//hec/temp/HI.postgres.converted';
COMMIT;

