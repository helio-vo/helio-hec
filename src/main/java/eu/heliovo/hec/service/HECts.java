/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package eu.heliovo.hec.service;


import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import java.io.StringWriter;
import java.io.FileInputStream;

import javax.jws.WebMethod;
import javax.jws.WebService;

import eu.heliovo.hec.votable.DATA;
import eu.heliovo.hec.votable.DataType;
import eu.heliovo.hec.votable.FIELD;
import eu.heliovo.hec.votable.INFO;
import eu.heliovo.hec.votable.PARAM;
import eu.heliovo.hec.votable.RESOURCE;
import eu.heliovo.hec.votable.TABLE;
import eu.heliovo.hec.votable.TABLEDATA;
import eu.heliovo.hec.votable.TD;
import eu.heliovo.hec.votable.TR;
import eu.heliovo.hec.votable.VOTABLE;

import java.sql.*;
import java.util.*;
/**
 *
 * @author antonio
 */
@WebService()
public class HECts {

     private static String jaxbInstance  = "hec.votable";
       Connection conn = null;
       Statement stmt = null;
        ResultSet rset = null;

        //PostgreSQL
        private static String dbconn        = "jdbc:mysql://localhost:3306/HEC";
        private static String username      = "root";
        private static String passwd        = "";
        private static String select        = " select * from goes_xray_flare ";
        private static String callsForName  = "org.postgresql.Driver";

        private static String strConn ;
        private static String userdb ;
        private static String passwddb ;
        private static String driver ;

         private Hashtable votdb = new Hashtable();
    /**
     * Web service operation
     */
    @WebMethod(operationName = "sql")
    public String sql(String sqlstr) {

        StringBuffer str = new StringBuffer();
	StringWriter sw = new StringWriter();
        JAXBContext jaxbCon = null;
        Marshaller  mar = null;
        VOTABLE vot = new VOTABLE();

        try {
                    jaxbCon = JAXBContext.newInstance(jaxbInstance);
                    //hec.votable.ObjectFactory objFactory = new hec.votable.ObjectFactory();
                    mar = jaxbCon.createMarshaller();



                    } catch ( JAXBException je)  {
                        je.printStackTrace();
             }

	try {
		this.rset = getData(sqlstr);
                vot = resultset2votable(this.rset,sqlstr);

                if (this.rset != null) this.rset.close();
                if (this.stmt != null) this.stmt.close();
                if (this.conn != null) this.conn.close();

            } catch ( SQLException sqle)  {
		sqle.printStackTrace();
            }

         try {
            mar.marshal(vot,sw);
            } catch ( JAXBException je)  {
                je.printStackTrace();
            }

        return sw.toString();
    }

    private ResultSet  getData(String sqlStr) throws SQLException {

        java.util.Properties prop = new java.util.Properties();
        //System.out.println("POLLO");
        try{
             prop.load(new FileInputStream("/opt/hec.config.txt"));
             driver = prop.getProperty("jdbc.Driver");
             userdb = prop.getProperty("jdbc.UserName");
             passwddb = prop.getProperty("jdbc.Passwd");
             strConn = prop.getProperty("jdbc.Connection");

             //System.out.println(driver);
            // System.out.println(username);
             //System.out.println(passwd);

        }catch (Exception ex) {

            ex.printStackTrace();
        }






        try {
                //oracle
            Class.forName(driver);
      // Step 2: Establish the connection to the database.
             String url = strConn;//"jdbc:msql://www.myserver.com:1114/contact_mgr";

             Connection conn = DriverManager.getConnection(url,userdb,passwddb);

             //DriverManager.registerDriver(new com.mysql.jdbc.Driver());
            // conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
/*
            DriverManager.registerDriver(new com.microsoft.jdbc.sqlserver.SQLServerDriver());
            conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
*/
            stmt = conn.createStatement();
            rset = stmt.executeQuery (sqlStr);



//            if (rset!= null) rset.close();
//            if (stmt!= null) stmt.close();
//            if (conn!= null) conn.close();

            } catch (Exception e) {
                //return ("<P> SQL error: <PRE> " + e + " </PRE> </P>\n");
            }


      return rset;
    }
private VOTABLE resultset2votable(ResultSet rs,String sql) {









//votdb.put("boolean",dt.BOOLEAN);
    //this.votdb.put("bit", "int");

    this.votdb.put("byte", "UNSIGNED_BYTE");
    this.votdb.put("short", "SHORT");
    this.votdb.put("int","INT");
    this.votdb.put("int4","INT");
    this.votdb.put("int8","LONG");
    this.votdb.put("long","LONG");
    this.votdb.put("char", "CHAR");
    this.votdb.put("bpchar", "CHAR");
    this.votdb.put("varchar", "CHAR");
    this.votdb.put("date","CHAR");

    this.votdb.put("timestamp", "CHAR");
    this.votdb.put("timestamptz","CHAR");
    this.votdb.put("text","CHAR");

    this.votdb.put("float", "FLOAT");
    this.votdb.put("float8", "DOUBLE");
    this.votdb.put("double", "DOUBLE");
    //oracle
    this.votdb.put("varschar2", "CHAR");
    this.votdb.put("number",   "INT");
    this.votdb.put("numeric",  "INT");
/*
    this.votdb.put("boolean","boolean");
    //this.votdb.put("bit", "int");
    this.votdb.put("byte", "unsignedByte");
    this.votdb.put("short", "short");
    this.votdb.put("int","int");
    this.votdb.put("long","long");
    this.votdb.put("char", "char");
    this.votdb.put("varchar", "char");
    this.votdb.put("float", "float");
    this.votdb.put("double", "double");
  */


        VOTABLE vot = new VOTABLE();

       // try {


            vot.setVersion("v1.1");

            RESOURCE res = new RESOURCE();
            res.setType("HEC RESULTS Table");

            INFO resInfo = new INFO();
            resInfo.setValue(sql);

            resInfo.setName("QUERY STATUS");
            res.getINFOOrCOOSYSOrPARAM().add(resInfo);

            PARAM param = new PARAM();

            TABLE table = new TABLE();
            table.setName("HEC RESULTS Table");

            TD td = new TD();
            TR tr = new TR();
            TABLEDATA tabledata = new TABLEDATA();



            ResultSetMetaData rsmd = null;
            FIELD field = null;
            int cCount =0;
                try {
                    rsmd = rs.getMetaData();
                    cCount = rsmd.getColumnCount();


                    // fill the FIELD elements
                    for ( int i=0; i< cCount;i++)
                    {
                         String key = null;
                         //if ( rsmd.getScale(i+1) >= 0 ) {

                         key = (String) votdb.get(rsmd.getColumnTypeName(i+1).toLowerCase());
                         // definire arraysize
                         // + rsmd.getScale(i+1) );
                         //key = (String) rsmd.getColumnTypeName(i+1) + rsmd.getScale(i+1) ;
                        //} else {
                         //   key = (String) rsmd.getColumnTypeName(i+1);
                        // }

                        String arraysize = "";
                        if (key == "CHAR") arraysize=" arraysize="+'"'+"*"+'"';
                        field = new FIELD();
                        field.setName(rsmd.getColumnName(i+1));
                        field.setDatatype(DataType.valueOf(key));
                        field.setID(rsmd.getColumnName(i+1));

                        table.getFIELDOrPARAMOrGROUP().add(field);
                    }



 // Add TR and TD
                    int rows =0 ;

                            rs.next();
                    do {
                        tr = new TR();

                        for ( int i=0; i < cCount ;i++)
                        {
                            td = new TD();
                            String val = rs.getString(i+1) + "";
                            td.setValue(val);
                            //td.getContent().add(Integer.toString(i));
                            tr.getTD().add(td);
                        }


                        tabledata.getTR().add(rows,tr);
                        rows++;
                      } while (rs.next());




                    } catch (SQLException sqsle) {
				}
          //tabledata.getTR().add(1,tr);
             DATA data = new DATA();
             data.setTABLEDATA(tabledata);


       //RESOURCE res = new RESOURCE();
           res.setName("HELIO-HEC");
           res.setID("HELIO-HEC");

           table.setDATA(data);
           //table.setID("tableID");
           table.setName("HEC");

        //table.getDATA().getTABLEDATA().getTR().add(0,tr);
           res.getTABLE().add(table);
           vot.getRESOURCE().add(res);


           vot.setVersion("1.1");

        //} catch ( JAXBException je) {

        //    je.printStackTrace();
       // }



        return vot;
    }
}
