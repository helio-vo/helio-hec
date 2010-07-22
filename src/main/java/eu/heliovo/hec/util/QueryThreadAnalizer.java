package eu.heliovo.hec.util;

import java.io.BufferedWriter;
import java.io.PipedWriter;
import java.sql.ResultSet;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;

import uk.ac.starlink.table.StarTable;
import uk.ac.starlink.table.jdbc.SequentialResultSetStarTable;
import uk.ac.starlink.votable.DataFormat;
import uk.ac.starlink.votable.VOSerializer;


public class QueryThreadAnalizer extends Thread{
	 private String jaxbInstance  = "hec.votable";
	 JAXBContext jaxbCon = null;
     Marshaller  mar = null;
     PipedWriter pw=null;
     BufferedWriter out=null;
     ResultSet rs=null;
     StarTable startTable=null;
	public QueryThreadAnalizer(StarTable startTable,PipedWriter pw){
		this.startTable = startTable;
		this.out=new BufferedWriter(pw);
	}
	public void run(){
		try {
			out.write("<helio:sqlResponse xmlns:helio=\"http://service.hec/\">");
			out.write( "<VOTABLE version='1.1' xmlns=\"http://www.ivoa.net/xml/VOTable/v1.1\">\n" );
			out.write( "<RESOURCE>\n" );
 	        out.write( "<DESCRIPTION>"+""+"</DESCRIPTION>\n" );
 	        out.write( "<INFO name='QUERY_STATUS' value='SUCCESS'/>\n");
 	        //writing the result set***sart table to output  
            VOSerializer.makeSerializer( DataFormat.TABLEDATA,startTable).writeInlineTableElement( out );
            out.write( "</RESOURCE>\n" );
            out.write( "</VOTABLE>\n" );
            out.write("</helio:sqlResponse>");
            out.flush();
            out.close();

        } catch (Exception je)  {
                je.printStackTrace();
        }  		
		
			
		
	 }
}
