/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

package eu.heliovo.hec.service;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;
import javax.xml.transform.Source;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMResult;
import javax.xml.transform.stream.StreamSource;
import javax.xml.ws.Provider;
import javax.xml.ws.ServiceMode;
import javax.xml.ws.WebServiceProvider;

import java.io.BufferedWriter;
import java.io.PipedReader;
import java.io.PipedWriter;
import java.io.StringWriter;
import java.io.FileInputStream;
import java.util.List;
import javax.jws.WebMethod;
import javax.jws.WebService;

import org.w3c.dom.Document;
import org.w3c.dom.Element;

import uk.ac.starlink.table.StarTable;
import uk.ac.starlink.table.jdbc.SequentialResultSetStarTable;
import uk.ac.starlink.votable.DataFormat;
import uk.ac.starlink.votable.VOSerializer;

import eu.heliovo.hec.util.QueryThreadAnalizer;
import eu.heliovo.hec.util.StandardTypeTable;
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
@WebServiceProvider(targetNamespace="",
	      serviceName="",
	      portName="")
	      
@ServiceMode(value=javax.xml.ws.Service.Mode.PAYLOAD)
public class HECSoapDispatcher implements Provider<Source> {
     private static String jaxbInstance  = "hec.votable";
       Connection conn = null;
       Statement stmt = null;
        ResultSet rset = null;
        PipedReader pr=null;
		PipedWriter pw=null;
        //PostgreSQL
        private static String dbconn        = "jdbc:mysql://msslxt.mssl.ucl.ac.uk:3306/helio_ics";
        private static String username      = "helio_admin";
        private static String passwd        = "majorca";
        //private static String select        = " select * from goes_xray_flare ";
        //private static String callsForName  = "org.postgresql.Driver";

         private Hashtable votdb = new Hashtable();
         StreamSource responseReader = null;	
    /**
     * Web service operation
     */
     @Override
     public Source invoke(Source request) {

        StringBuffer str = new StringBuffer();
        StringWriter sw = new StringWriter();
        JAXBContext jaxbCon = null;
        Marshaller  mar = null;
        VOTABLE vot = new VOTABLE();
        String sqlstr="";

        

	try {
		 Element inputDoc=toDocument(request);
		 pr = new PipedReader();
		 pw = new PipedWriter(pr);
		//Setting for No Of Rows parameter.
		 if(inputDoc.getElementsByTagNameNS("*","sql").getLength()>0 && inputDoc.getElementsByTagNameNS("*","sql").item(0).getFirstChild()!=null){
			 sqlstr = inputDoc.getElementsByTagNameNS("*","sql").item(0).getFirstChild().getNodeValue();
			 
		 }
		 
		this.rset = getResultsFromDatabase(sqlstr);
		StarTable startTable= new StandardTypeTable( new SequentialResultSetStarTable( rset ));
		new QueryThreadAnalizer(startTable,pw).start();
		
                if (this.conn != null) this.conn.close();

       } catch ( Exception sqle)  {
		sqle.printStackTrace();
        }
   
        responseReader=new StreamSource(pr);
        
        return responseReader;
    }

    private ResultSet  getResultsFromDatabase(String sqlStr) throws SQLException {

        try {
             
        	 Class.forName("com.mysql.jdbc.Driver");
				
			
             conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
/*
            DriverManager.registerDriver(new com.microsoft.jdbc.sqlserver.SQLServerDriver());
            conn = DriverManager.getConnection(this.dbconn,this.username,this.passwd);
*/
            stmt = conn.createStatement();
            rset = stmt.executeQuery (sqlStr);


            } catch (Exception e) {
                //return ("<P> SQL error: <PRE> " + e + " </PRE> </P>\n");
            	System.out.println(" Exception in getResultsFromDatabase() : "+e.getMessage());
            }


      return rset;
    }
    
    

	/*
	 * Method used to convert Source to dom object.
	*/
	private synchronized Element toDocument(Source src) throws TransformerException {
	    DOMResult result = new DOMResult();
	    Transformer transformer = TransformerFactory.newInstance().newTransformer();
	    try {
	        transformer.transform(src, result);
	    } catch (TransformerException te) {
	        throw new TransformerException("Error while applying template", te);
	    }
	    Element root = ((Document)result.getNode()).getDocumentElement();
	   return root;
	}

}

