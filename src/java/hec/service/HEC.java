/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package hec.service;




import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import java.io.StringWriter;
import java.io.FileInputStream;


import hec.votable.DATA;
import hec.votable.DataType;
import hec.votable.FIELD;
import hec.votable.INFO;
import hec.votable.PARAM;
import hec.votable.RESOURCE;
import hec.votable.TABLE;
import hec.votable.TABLEDATA;
import hec.votable.TD;
import hec.votable.TR;
import hec.votable.VOTABLE;
import java.util.List;
import javax.jws.WebMethod;
import javax.jws.WebService;
import javax.ejb.Stateless;
import java.sql.*;
import java.util.*;
/**
 *
 * @author antonio
 */
@WebService()
//@Stateless()
public class HEC {
     private static String jaxbInstance  = "hec.votable";
       Connection conn = null;
       Statement stmt = null;
        ResultSet rset = null;

        //PostgreSQL
        private static String dbconn        = "jdbc:mysql://localhost:3306/HEC";
        private static String username      = "root";
        private static String passwd        = "Jamimaan";
        //private static String select        = " select * from goes_xray_flare ";
        //private static String callsForName  = "org.postgresql.Driver";

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

        java.util.Properties prop = new Properties();
        try {
            FileInputStream in = new FileInputStream("defaultProperties");
            prop.load(in);

        } catch (Exception ex) {


        }

       



        try {
             

             DriverManager.registerDriver(new com.mysql.jdbc.Driver());
             conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
/*
            DriverManager.registerDriver(new com.microsoft.jdbc.sqlserver.SQLServerDriver());
            conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
*/
            stmt = conn.createStatement();
            rset = stmt.executeQuery (sqlStr);


            } catch (SQLException e) {
                //return ("<P> SQL error: <PRE> " + e + " </PRE> </P>\n");
            }


      return rset;
    }
private VOTABLE resultset2votable(ResultSet rs,String sql) {



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


            vot.setVersion("v1.1");

            RESOURCE res = new RESOURCE();
            res.setType("RESULTS");

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
                        
                         key = (String) votdb.get(rsmd.getColumnTypeName(i+1).toLowerCase());

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
       
        return vot;
    }

}

