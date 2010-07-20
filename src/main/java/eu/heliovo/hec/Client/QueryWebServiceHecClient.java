package eu.heliovo.hec.Client;

import java.io.StringWriter;
import java.rmi.RemoteException;
import java.util.Vector;
import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.rpc.ServiceException;
import javax.xml.transform.Source;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerException;
import javax.xml.transform.TransformerFactory;
import javax.xml.transform.dom.DOMResult;
import javax.xml.transform.dom.DOMSource;
import javax.xml.transform.stream.StreamResult;

import org.apache.axis.client.Call;
import org.apache.axis.client.Service;
import org.apache.axis.message.SOAPBodyElement;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;


public class QueryWebServiceHecClient {

	public static void main(String args[]) throws RemoteException, ServiceException, Exception
	{		
 
		Document doc = null; 
		DocumentBuilder registryBuilder = null;
		registryBuilder =DocumentBuilderFactory.newInstance(). newDocumentBuilder();
		doc = registryBuilder.newDocument();
		//change this to helio:TimeSearch
		//the getSoapBodyNamespaceURI() you can hard code it should be the same namespace you used to register in the SoapServlet.
		Element root = doc.createElementNS("http://service.hec/", "helio:VoTableQueryResponse");			
		//This configuration for TIME.		  	
		Element xqueryElemStartTime = doc.createElementNS("http://service.hec/","helio:sql");			
		//xquery should be 'time' so 2009-10-09T00:00:00/2009-10-09T01:00:00
		xqueryElemStartTime.appendChild(doc.createTextNode("select * from goes_xray_flare limit 200"));	
		
		root.appendChild(xqueryElemStartTime); // ID To Element.
		doc.appendChild(root);
		 System.out.println("THE REQUEST FROM SERVICE = " +getStringFromDoc(doc) );
		//Calling the service.
		callService(doc,"VoTableQueryResponse","VoTableQueryResponse");
		
	}
	
	protected static Document callService(Document soapBody, String name, String soapActionURI) throws RemoteException , ServiceException, Exception {
	       Vector result = null;      
	       Document resultDoc =newDocument();
	       Document wsDoc = null;
	       NodeList vResources = null;
	       //get a call object
	       Call call = getCall();
	           
	           //When trying to call a Web Service with this client deployed on Microsoft .Net
	           //the SoapActionURI was important to Microsoft as a requirement though it should not be.
	           //I think this might have been fixed some years ago, but leaving it here for now.
	           call.setSOAPActionURI(soapActionURI);	
	           call.setTimeout(500000);
	           //create the soap body to be placed in the
	           //outgoing soap message.
	           SOAPBodyElement sbeRequest = new SOAPBodyElement(soapBody.getDocumentElement());	           
	           //go ahead and set the name and namespace on the soap body
	           //not sure if this is that important since I am constructing the soap body xml myself.
	           //I believe it should not be that important but if my memory serves me correct
	           //Axis seemed to throw a NullPointerException if these two methods were not set.
	           sbeRequest.setName(name);
	           //Setting target name space.
	           sbeRequest.setNamespaceURI("http://service.hec/");	           
	           //call the web service, on axis-message style it
	           //comes back as a vector of soabodyelements.
	           result = (Vector)call.invoke(new Object[] { sbeRequest });
	           SOAPBodyElement sbe = null;
	           if (result.size() > 0) {
	              sbe = (SOAPBodyElement)result.get(0);
	              wsDoc = sbe.getAsDocument();	  	              
	              System.out.println("THE RESULTDOC FROM SERVICE = " +getStringFromDoc(wsDoc) );
	              if(wsDoc.getDocumentElement() == null) {
	                 
	                  throw new Exception("Error Nothing returned in the Message from the Server, should always be something.");
	              }          
	              resultDoc = wsDoc;
	              return resultDoc;
	           }
	         
	           throw new Exception("ERROR RESULT FROM WEB SERVICE WAS LITERALLY NOTHING IN THE MESSAGE SHOULD NOT HAPPEN.");
	           //return null;
	   }
	   	   
	    /**
	    * Method to establish a Service and a Call to the server side web service.
	    * @return Call object which has the necessary properties set for an Axis message style.
	    * @throws Exception
	    * @todo there's code similar to this in eac of the delegate classes. could it be moved into a common baseclass / helper class.	   
	    */
	   private static Call getCall() throws ServiceException {	       	     
	      Call _call = null;
	      Service service = new Service();
	      _call = (Call)service.createCall();
	      
	
	      //Setting end point(  Note***** Antonio you have to give ur url as http://hostname:hostport/context_path/HECProvider)
	      _call.setTargetEndpointAddress("http://localhost:8080/helio-hec/HECProvider");
	      _call.setSOAPActionURI("");
	      //_call.setOperationStyle(org.apache.axis.enum.Style.MESSAGE);
	      //_call.setOperationUse(org.apache.axis.enum.Use.LITERAL);
	      _call.setEncodingStyle(null);
	      return _call;
	   }
	   
	   /*
		 * Method used to convert Source to dom object.
		 */
		private  Document toDocument(Source src) throws TransformerException {
	        DOMResult result = new DOMResult();
	        Transformer transformer = TransformerFactory.newInstance().newTransformer();
	        try {
	            transformer.transform(src, result);
	        } catch (TransformerException te) {
	            throw new TransformerException("Error while applying template", te);
	        }
	        
	        Document root = (Document) (result.getNode());//.getOwnerDocument();
	       return root;
		}
		
		
		public static String getStringFromDoc(org.w3c.dom.Document doc)    {
	        try
	        {
	        	 Transformer transformer = TransformerFactory.newInstance().newTransformer();
	        	  StreamResult result = new StreamResult(new StringWriter());
	        	  DOMSource source = new DOMSource(doc);
	        	  transformer.transform(source, result);
	        	  return result.getWriter().toString();

	        }
	        catch(TransformerException ex)
	        {
	           ex.printStackTrace();
	           return null;
	        }
	    }


		//Creates a new DOM object
		public static Document newDocument() throws Exception{
			DocumentBuilderFactory documentBuilderFactory = DocumentBuilderFactory.newInstance();
		    DocumentBuilder documentBuilder = documentBuilderFactory.newDocumentBuilder();
		    return documentBuilder.newDocument();
		}
}
